{extends file='layout.tpl'}

{block name="content"}
<div class="card my-5 mx-auto" style="max-width: 500px;">
    {if $message|lower contains 'succes'}
        <div class="card-header bg-success text-white">
            Database test geslaagd
        </div>
        <div class="card-body">
            <p class="card-text">De verbinding met de database is succesvol gemaakt.</p>
        </div>
    {else}
        <div class="card-header bg-danger text-white">
            Database test mislukt
        </div>
        <div class="card-body">
            <p class="card-text">
                {if isset($error)}{$error}{else}Er is een onbekende fout opgetreden.{/if}
            </p>
        </div>
    {/if}
    <div class="card-footer text-end">
        <a href="index.php" class="btn btn-secondary">Terug naar Home</a>
    </div>
</div>
{/block}