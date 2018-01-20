<?php
/**
 * Copyright © 2014, Ambroise Maupate and Julien Blanchet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name of the ROADIZ shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
 *
 * @file User.php
 * @author Ambroise Maupate
 */

namespace RZ\Roadiz\Core\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use RZ\Roadiz\Core\AbstractEntities\AbstractHuman;
use RZ\Roadiz\Utils\Security\SaltGenerator;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User Entity.
 *
 * @ORM\Entity(repositoryClass="RZ\Roadiz\Core\Repositories\UserRepository")
 * @ORM\Table(name="users", indexes={
 *     @ORM\Index(columns={"enabled"}),
 *     @ORM\Index(columns={"expired"}),
 *     @ORM\Index(columns={"expires_at"}),
 *     @ORM\Index(columns={"locale"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class User extends AbstractHuman implements AdvancedUserInterface
{
    /**
     * Email confirmation link TTL (in seconds) to change
     * password.
     *
     * @var int
     */
    const CONFIRMATION_TTL = 300;

    /**
     * @var bool
     */
    protected $sendCreationConfirmationEmail;

    /**
     * @ORM\Column(type="string", name="facebook_name", unique=false, nullable=true)
     */
    protected $facebookName = null;

    /**
     * @ORM\Column(type="text", name="picture_url", nullable=true)
     */
    protected $pictureUrl = '';
    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default" = true})
     */
    protected $enabled = true;
    /**
     * @ORM\Column(name="confirmation_token", type="string", unique=true, nullable=true)
     * @var string
     */
    protected $confirmationToken;
    /**
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $passwordRequestedAt;
    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $username;
    /**
     * The salt to use for hashing
     *
     * @ORM\Column(name="salt", type="string")
     * @var string
     */
    private $salt;
    /**
     * Encrypted password.
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $password;
    /**
     * Plain password. Used for model validation.
     * **Must not be persisted.**
     *
     * @var string
     */
    private $plainPassword;
    /**
     * @var \DateTime
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;
    /**
     * @ORM\ManyToMany(targetEntity="RZ\Roadiz\Core\Entities\Role")
     * @ORM\JoinTable(name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $roles;
    /**
     * Names of current User roles
     * to be compatible with symfony security scheme
     *
     * @var array
     */
    private $rolesNames = null;
    /**
     * @ORM\ManyToMany(targetEntity="RZ\Roadiz\Core\Entities\Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection
     */
    private $groups;
    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default" = false})
     */
    private $expired = false;
    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default" = false})
     */
    private $locked = false;
    /**
     * @ORM\Column(name="credentials_expires_at", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $credentialsExpiresAt;
    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="credentials_expired", nullable=false, options={"default" = false})
     */
    private $credentialsExpired = false;
    /**
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $expiresAt;
    /**
     * @ORM\ManyToOne(targetEntity="RZ\Roadiz\Core\Entities\Node")
     * @ORM\JoinColumn(name="chroot_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var Node
     */
    private $chroot;

    /**
     * @var null|string
     * @ORM\Column(name="locale", type="string", nullable=true, length=7)
     */
    private $locale = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->sendCreationConfirmationEmail(false);

        $saltGenerator = new SaltGenerator();
        $this->setSalt($saltGenerator->generateSalt());
    }

    /**
     * Set if we need Roadiz to send a default email
     * when User will be persisted.
     *
     * @param bool $sendCreationConfirmationEmail
     * @return User
     */
    public function sendCreationConfirmationEmail($sendCreationConfirmationEmail)
    {
        $this->sendCreationConfirmationEmail = $sendCreationConfirmationEmail;
        return $this;
    }

    /**
     * Tells if we need Roadiz to send a default email
     * when User will be persisted. Default: false.
     *
     * @return bool
     */
    public function willSendCreationConfirmationEmail()
    {
        return $this->sendCreationConfirmationEmail;
    }

    /**
     * Get available user name data, first name and last name
     * or username as a last try.
     *
     * @return string
     */
    public function getIdentifier()
    {
        if ($this->getFirstName() != "" && $this->getLastName() != "") {
            return $this->getFirstName() . " " . $this->getLastName();
        } elseif ($this->getFirstName() != "") {
            return $this->getFirstName();
        } else {
            return $this->getUsername();
        }
    }

    /**
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get facebook profile name to grab public infos such as picture
     *
     * @return string
     */
    public function getFacebookName()
    {
        return $this->facebookName;
    }

    /**
     * @param string $facebookName
     *
     * @return string $facebookName
     */
    public function setFacebookName($facebookName)
    {
        $this->facebookName = $facebookName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     *
     * @return string $pictureURL
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     *
     * @return string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string $plainPassword
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        if (null !== $plainPassword && $plainPassword != '') {
            /*
             * We MUST change password to trigger preUpdate lifeCycle event.
             */
            $this->password = '--password-changed--' . uniqid();
        }
        return $this;
    }

    /**
     * @return \DateTime $lastLogin
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     *
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get random string sent to the user email address in order to verify it.
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set random string sent to the user email address in order to verify it.
     *
     * @param string $confirmationToken
     * @return $this
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Check if password reset request has expired.
     *
     * @param  int $ttl Password request time to live.
     *
     * @return boolean
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * Gets the timestamp that the user requested a password reset.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * Sets the timestamp that the user requested a password reset.
     *
     * @param \DateTime|null $date
     * @return $this
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;

        return $this;
    }

    /**
     * Add a role object to current user.
     *
     * @param \RZ\Roadiz\Core\Entities\Role $role
     *
     * @return $this
     */
    public function addRole(Role $role)
    {
        if (!$this->getRolesEntities()->contains($role)) {
            $this->getRolesEntities()->add($role);
        }

        return $this;
    }

    /**
     * Get roles entities
     *
     * @return ArrayCollection
     */
    public function getRolesEntities()
    {
        return $this->roles;
    }

    /**
     * Remove role from current user.
     *
     * @param \RZ\Roadiz\Core\Entities\Role $role
     *
     * @return $this
     */
    public function removeRole(Role $role)
    {
        if ($this->getRolesEntities()->contains($role)) {
            $this->getRolesEntities()->removeElement($role);
        }

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * @return void
     */
    public function eraseCredentials()
    {
        $this->setPlainPassword('');
    }

    /**
     * Insert user into group.
     *
     * @param \RZ\Roadiz\Core\Entities\Group $group
     *
     * @return $this
     */
    public function addGroup(Group $group)
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Remove user from group
     *
     * @param \RZ\Roadiz\Core\Entities\Group $group
     *
     * @return $this
     */
    public function removeGroup(Group $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    /**
     * Get current user groups name.
     *
     * @return array Array of strings
     */
    public function getGroupNames()
    {
        $names = [];
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * Return strictly forced expiration status.
     *
     * @return boolean
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param boolean $expired
     * @return $this
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Combines expiresAt date-time limit AND expired boolean value.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool    true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        if ($this->expiresAt !== null &&
            $this->expiresAt->getTimestamp() < time()) {
            return false;
        }

        return !$this->expired;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    public function setLocked($locked)
    {
        $this->locked = (boolean)$locked;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param User $user
     *
     * @return boolean
     */
    public function equals(User $user)
    {
        return (
            $this->username == $user->getUsername() ||
            $this->email == $user->getEmail()
        );
    }

    /**
     * @return \DateTime
     */
    public function getCredentialsExpiresAt()
    {
        return $this->credentialsExpiresAt;
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function setCredentialsExpiresAt(\DateTime $date = null)
    {
        $this->credentialsExpiresAt = $date;

        return $this;
    }

    /**
     * Return strictly forced credentials expiration status.
     *
     * @return boolean
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }

    /**
     * @param boolean $newcredentialsExpired
     * @return $this
     */
    public function setCredentialsExpired($newcredentialsExpired)
    {
        $this->credentialsExpired = $newcredentialsExpired;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function setExpiresAt(\DateTime $date = null)
    {
        $this->expiresAt = $date;

        return $this;
    }

    /**
     * @return Node
     */
    public function getChroot()
    {
        return $this->chroot;
    }

    /**
     * @param Node $chroot
     * @return User
     */
    public function setChroot(Node $chroot = null)
    {
        $this->chroot = $chroot;

        return $this;
    }

    /**
     * Get prototype abstract gravatar url.
     *
     * @return string
     */
    public function getGravatarUrl()
    {
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->getEmail()))) . "?d=identicon&s=200";
    }

    /**
     * @return string $text
     */
    public function __toString()
    {
        $text = $this->getUsername() . ' <' . $this->getEmail() . '>' . PHP_EOL;
        $text .= '— Enabled: ' . ($this->isEnabled() ? 'Yes' : 'No') . PHP_EOL;
        $text .= '— Expired: ' . ($this->isCredentialsNonExpired() ? 'No' : 'Yes') . PHP_EOL;
        $text .= "— Roles: " . implode(', ', $this->getRoles());

        return $text;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     *
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean)$enabled;

        return $this;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Combines credentialsExpiresAt date-time limit AND credentialsExpired boolean value.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool    true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        if ($this->credentialsExpiresAt !== null &&
            $this->credentialsExpiresAt->getTimestamp() < time()) {
            return false;
        }

        return !$this->credentialsExpired;
    }

    /**
     * Get roles names as a simple array, combining groups roles.
     *
     * @return array
     */
    public function getRoles()
    {
        $this->rolesNames = [];
        foreach ($this->getRolesEntities() as $role) {
            if (null !== $role) {
                $this->rolesNames[] = $role->getName();
            }
        }

        foreach ($this->getGroups() as $group) {
            // User roles > Groups roles
            $this->rolesNames = array_merge($group->getRoles(), $this->rolesNames);
        }

        // we need to make sure to have at least one role
        $this->rolesNames[] = Role::ROLE_DEFAULT;
        $this->rolesNames = array_unique($this->rolesNames);

        return $this->rolesNames;
    }

    /**
     * @return null|string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param null|string $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
}
