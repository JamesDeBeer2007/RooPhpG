{extends file='layout.tpl'}

{block name="content"}
<div class="container my-5">
    <div class="alert alert-danger text-center" role="alert">
        {if isset($message)}
            {$message}
        {else}
            Er is een fout opgetreden.
        {/if}
    </div>
</div>
{/block}