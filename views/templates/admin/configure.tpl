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

<div class="panel">
    <h3><i class="icon icon-credit-card"></i> {l s='LegalBlink Module' d='Modules.Legalblink.Configure'}</h3>
    <p>
        <strong>{l s='LegalBlink Module to add links provided' d='Modules.Legalblink.Configure'} <a
                    href="https://app.legalblink.it/">{l s='here' d='Modules.Legalblink.Configure'}</a></strong><br/>
        {l s='This module helps you to add LegalBlink links in footer.' d='Modules.Legalblink.Configure'}
    </p>
</div>

<div class="panel">
    <h3><i class="icon icon-tags"></i> {l s='Documentation' d='Modules.Legalblink.Configure'}</h3>
    <p>
        &raquo; {l s='You can get a PDF documentation to configure this module' d='Modules.Legalblink.Configure'}:
    <ul>
        <li><a href="#" target="_blank">{l s='English' d='Modules.Legalblink.Configure'}</a></li>
        <li><a href="#" target="_blank">{l s='Italian' d='Modules.Legalblink.Configure'}</a></li>
    </ul>
    </p>
</div>
{if $alert_iframe_disable}
    <div class="panel">
        <h3><i class="icon icon-tags"></i> {l s='Information' d='Modules.Legalblink.Configure'}</h3>
        <p style="font-weight: bold;">
            <i class="icon-warning-sign"></i> {l s='You must enable IFRAME to set policies into CMS pages. Please enable in Configure > Shop Parameters > General page' d='Modules.Legalblink.Configure'}
        </p>
    </div>
{/if}
