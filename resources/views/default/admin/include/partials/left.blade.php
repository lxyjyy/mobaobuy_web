<div class="admin-main-left">
    <div class="admincj_nav">

        <div class="navLeftTab" id="adminNavTabs_{$nav.type}" style="display:block;">


            <div class="item">
                <div class="tit"><a href="javascript:void(0)"><i class="nav_icon icon_03_goods_storage"></i><h4 >库存管理</h4></a></div>

                    <div class="sub-menu" style="display:block;" >
                        <ul>
                            {foreach from=$menu.children item=child name=childfoo key=key}
                            <li {if $smarty.foreach.childfoo.first}class="curr"{/if}><s></s>
                            {if $key eq '03_visualnews'}
                            <a href="{$child.action}" target="_blank" >{$child.label}</a>
                            {else}<a href="javascript:void(0);" data-url="{$child.action}" data-param="{$nav.type}|{$key}" target="workspace" >{$child.label}</a>
                            {/if}
                            </li>
                            {/foreach}
                        </ul>
                    </div>

                    <div class="sub-menu"{if $nav.type eq 'home'} style="display:block;"{/if}>
                    <ul>
                            {foreach from=$menu.children item=child name=childfoo key=key}
                            <li {if $smarty.foreach.childfoo.first}class="curr"{/if}><s></s>
                            {if $key eq '03_visualnews'}
                            <a href="{$child.action}" target="_blank" >{$child.label}</a>
                            {else}<a href="javascript:void(0);" data-url="{$child.action}" data-param="{$nav.type}|{$key}" target="workspace" >{$child.label}</a>
                            {/if}
                            </li>
                            {/foreach}
                        </ul>
                    </div>

            </div>


        {if !$menu.action}
        <div class="item{if $k eq '02_promotion'} fold_item{/if}">
                <div class="tit"><a href="javascript:void(0)"><i class="nav_icon icon_{$k}"></i><h4{if $k eq '24_wxapp'} class="txt_tx"{/if}>{$menu.label}</h4></a></div>
                {if $menu.children}
                <div class="sub-menu"{if $nav.type eq 'home'} style="display:block;"{/if}>
                <ul>
                    {foreach from=$menu.children item=child name=childfoo key=key}
                    <li {if $smarty.foreach.childfoo.first}class="curr"{/if}><s></s>
                    {if $key eq '03_visualnews'}
                    <a href="{$child.action}" target="_blank" >{$child.label}</a>
                    {else}<a href="javascript:void(0);" data-url="{$child.action}" data-param="{$nav.type}|{$key}" target="workspace" >{$child.label}</a>
                    {/if}
                    </li>
                    {/foreach}
                </ul>
            </div>
            {/if}
        </div>
        {/if}

        </div>


    {foreach from=$nav_top item=nav}
    <div class="navLeftTab" id="adminNavTabs_{$nav.type}" style="display:{if $nav.type eq 'home'}block{else}none{/if};">
        {foreach from=$nav.children item=menu key=k name=foo}
        {if !$menu.action}
        <div class="item{if $k eq '02_promotion'} fold_item{/if}">
            <div class="tit"><a href="javascript:void(0)"><i class="nav_icon icon_{$k}"></i><h4{if $k eq '24_wxapp'} class="txt_tx"{/if}>{$menu.label}</h4></a></div>
            {if $menu.children}
            <div class="sub-menu"{if $nav.type eq 'home'} style="display:block;"{/if}>
            <ul>
                {foreach from=$menu.children item=child name=childfoo key=key}
                <li {if $smarty.foreach.childfoo.first}class="curr"{/if}><s></s>
                {if $key eq '03_visualnews'}
                <a href="{$child.action}" target="_blank" >{$child.label}</a>
                {else}<a href="javascript:void(0);" data-url="{$child.action}" data-param="{$nav.type}|{$key}" target="workspace" >{$child.label}</a>
                {/if}
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
    {/if}
    {/foreach}
</div>
{/foreach}
    </div>
</div>