{*
* Copyright since 2007 Carmine Di Gruttola
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    cdigruttola <c.digruttola@hotmail.it>
*  @copyright Copyright since 2007 Carmine Di Gruttola
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div id="legalblink" class="col-md-6 links wrapper">
    <p class="h3 hidden-sm-down">{l s='Security and transparency' d='Modules.Legalblink.Preferences'}</p>
    {assign var=_expand_id value=10|mt_rand:100000}
    <div class="title clearfix hidden-md-up" data-target="#footer_sub_menu_{$_expand_id}" data-toggle="collapse">
        <span class="h3">{l s='Security and transparency' d='Modules.Legalblink.Preferences'}</span>
        <span class="pull-xs-right">
          <span class="navbar-toggler collapse-icons">
            <i class="material-icons add">&#xE313;</i>
            <i class="material-icons remove">&#xE316;</i>
          </span>
        </span>
    </div>
    <ul id="footer_sub_menu_{$_expand_id}" class="collapse">
        <li><a href="#"
               class="lb-cs-settings-link">{l s='Update cookies preferences' d='Modules.Legalblink.Preferences'}</a>
        </li>
        {foreach from=$elements item=element}
            <li><a href="{$element.link}">{$element.text}</a></li>
        {/foreach}
    </ul>
</div>
