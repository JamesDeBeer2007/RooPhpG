{extends file='layout.tpl'}

{block name="content"}
<div class="card my-4 mx-auto" style="max-width: 500px;">
    <div class="card-header">
        <h2 class="mb-0">Battle Arena</h2>
    </div>
    <div class="card-body">
        <form action="index.php?page=startBattle" method="POST">
            <div class="mb-3">
                <label for="character1" class="form-label">Kies Character 1</label>
                <select class="form-select" id="character1" name="character1" required>
                    <option value="" disabled selected>Selecteer een character</option>
                    {foreach from=$characters item=character}
                        <option value="{$character->getName()}">{$character->getName()} ({$character->getRole()})</option>
                    {/foreach}
                </select>
            </div>
            <div class="mb-3">
                <label for="character2" class="form-label">Kies Character 2</label>
                <select class="form-select" id="character2" name="character2" required>
                    <option value="" disabled selected>Selecteer een character</option>
                    {foreach from=$characters item=character}
                        <option value="{$character->getName()}">{$character->getName()} ({$character->getRole()})</option>
                    {/foreach}
                </select>
            </div>
            <button type="submit" class="btn btn-danger w-100">Start Battle</button>
        </form>
    </div>
</div>
{/block}