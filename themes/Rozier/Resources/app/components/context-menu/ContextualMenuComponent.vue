<!--
  - Copyright (c) 2017. Ambroise Maupate and Julien Blanchet
  -
  - Permission is hereby granted, free of charge, to any person obtaining a copy
  - of this software and associated documentation files (the "Software"), to deal
  - in the Software without restriction, including without limitation the rights
  - to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  - copies of the Software, and to permit persons to whom the Software is furnished
  - to do so, subject to the following conditions:
  - The above copyright notice and this permission notice shall be included in all
  - copies or substantial portions of the Software.
  -
  - THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
  - OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  - FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
  - THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  - LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  - OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
  - IN THE SOFTWARE.
  -
  - Except as contained in this notice, the name of the ROADIZ shall not
  - be used in advertising or otherwise to promote the sale, use or other dealings
  - in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
  -
  - @file ContextualMenuComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <nav class="uk-dropdown uk-dropdown-small uk-dropdown-flip uk-dropdown-bottom" v-if="node">
        <div class="vertical-nodetype">
            <p class="text">Page</p>
        </div>
        <div class="uk-nav uk-nav-dropdown">
            <p class="add-child"><a title="Ajouter un nœud enfant à «bonjour-roadiz»" href="/dev.php/rz-admin/nodes/add-child/4">
                <i class="uk-icon-rz-plus"></i> Ajouter un nœud enfant</a>
            </p>
            <div class="wrapper">
                <header class="uk-nav-header">Actions</header>
                <p>
                    <a title="bonjour-roadiz" href="/dev.php/rz-admin/nodes/edit/4">
                        <i class="uk-icon-rz-pencil"></i> Éditer le nœud</a>
                </p>
                <p>
                    <a title="Déplacer en début de liste" href="#" class="move-node-first-position smaller-text">
                        <i class="uk-icon-arrow-up"></i> Déplacer en début de liste
                    </a>
                </p>
                <p>
                    <a title="Déplacer en fin de liste" href="#" class="move-node-last-position smaller-text">
                        <i class="uk-icon-arrow-down"></i> Déplacer en fin de liste
                    </a>
                </p>
                <p>
                    <a title="Supprimer le nœud «bonjour-roadiz»" href="/dev.php/rz-admin/nodes/delete/4?referer=http%3A//roadiz.local/dev.php/rz-admin/nodes/edit/9/source/1"><i class="uk-icon-rz-trash-o"></i> Supprimer le nœud                                </a></p></div><div class="wrapper node-actions"><header class="uk-nav-header">Statuts</header><p><a data-action="hide" data-status="visible" data-value="0" title="Cacher" href="#" class="hide-node"><i class="uk-icon-eye-slash"></i> Cacher</a></p><p><a data-action="unpublish" data-status="status" data-value="10" title="Dépublier" href="#" class="unpublish-node"><i class="uk-icon-rz-draft-nodes"></i> Dépublier</a></p><p><a title="Publier la descendance" href="/dev.php/rz-admin/nodes/publish-all/4" class="publish-all-node"><i class="uk-icon-rz-published-mini"></i> Publier la descendance</a></p><p><a data-action="duplicate" title="Dupliquer" href="#" class="duplicate-node"><i class="uk-icon-rz-duplicate"></i> Dupliquer</a></p></div></div>
    </nav>
</template>
<script>
    import { ContextualMenu } from './ContextualMenuScript'
    export default ContextualMenu
</script>
<style lang="scss" scoped>
    .uk-dropdown {
        position: fixed;
        display: block !important;
    }
</style>
