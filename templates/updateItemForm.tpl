{extends file="layout.tpl"}

{block name="content"}
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            Update Item
        </div>
        <div class="card-body">
            <form action="index.php?page=saveUpdatedItem" method="POST">
                <input type="hidden" name="id" value="{$item->getId()}">

                <div class="mb-3">
                    <label for="name" class="form-label">Naam</label>
                    <input type="text" class="form-control" id="name" name="name" value="{$item->getName()|escape}" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="weapon" {if $item->getType() == 'weapon'}selected{/if}>Weapon</option>
                        <option value="armor" {if $item->getType() == 'armor'}selected{/if}>Armor</option>
                        <option value="consumable" {if $item->getType() == 'consumable'}selected{/if}>Consumable</option>
                        <option value="misc" {if $item->getType() == 'misc'}selected{/if}>Misc</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Waarde</label>
                    <input type="number" class="form-control" id="value" name="value" value="{$item->getValue()}" step="0.01" min="0" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Item</button>
                <a href="index.php?page=listItems" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
{/block}