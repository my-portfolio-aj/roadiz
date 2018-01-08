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
    <nav
        ref="contextMenu"
        @click.stop
        @contextmenu.stop
        :style="ctxStyle"
        class="ctx-menu-container uk-dropdown uk-dropdown-small uk-dropdown-flip uk-dropdown-bottom">
        <div style="background-color:transparent" class="ctx open">
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
        </div>
    </nav>
</template>
<script>
    import { ContextualMenu } from './ContextualMenuScript'
    export default ContextualMenu
</script>
<style lang="scss" scoped>
    /*.uk-dropdown {*/
        /*position: fixed;*/
        /*display: block !important;*/
    /*}*/


    .ctx {
        position: relative;
    }

    .ctx-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        font-size: .9rem;
        color: #373a3c;
        text-align: left;
        list-style: none;
        background-color: #fff;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: .25rem;
        -moz-box-shadow:0 0 5px #CCC; -webkit-box-shadow:0 0 5px #CCC; box-shadow:0 0 5px #CCC
    }

    .ctx-divider {
        height: 1px;
        margin: .5rem 0;
        overflow: hidden;
        background-color: #e5e5e5;
    }

    .ctx-item {
        display: block;
        /*width: 100%;*/
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.5;
        color: #373a3c;
        text-align: inherit;
        white-space: nowrap;
        background: none;
        border: 0;
        cursor: default;
    }

    .ctx-item:focus,
    .ctx-item:hover {
        color: #2b2d2f;
        text-decoration: none;
        background-color: #f5f5f5;
        cursor: normal;
    }

    .ctx-item.active,
    .ctx-item.active:focus,
    .ctx-item.active:hover {
        color: #fff;
        text-decoration: none;
        background-color: #0275d8;
        outline: 0;
    }

    .ctx-item.disabled,
    .ctx-item.disabled:focus,
    .ctx-item.disabled:hover {
        color: #818a91;
    }

    .ctx-item.disabled:focus,
    .ctx-item.disabled:hover {
        text-decoration: none;
        cursor: not-allowed;
        background-color: transparent;
        background-image: none;
        filter: "progid:DXImageTransform.Microsoft.gradient(enabled = false)";
    }

    .open > .ctx-menu {
        display: block;
    }

    .open > a {
        outline: 0;
    }

    .ctx-menu-right {
        right: 0;
        left: auto;
    }

    .ctx-menu-left {
        right: auto;
        left: 0;
    }

    .ctx-header {
        display: block;
        padding: 3px 20px;
        font-size: .9rem;
        line-height: 1.5;
        color: #818a91;
        white-space: nowrap;
    }

    .ctx-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 990;
    }

    .pull-right > .ctx-menu {
        right: 0;
        left: auto;
    }

    .ctx-menu-container {
        position: fixed;
        padding: 0;
        border: 1px solid #bbb;
        background-color: whitesmoke;
        z-index: 99999;
        max-width: 220px;
        box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 4px 15px 0 rgba(0, 0, 0, 0.15);
    }
</style>
