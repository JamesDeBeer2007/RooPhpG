{extends file="layout.tpl"}

{block name="content"}
<div class="container mt-5">
    <div class="alert alert-danger" role="alert">
        <strong>Warning!</strong> You are about to delete the following item. This action cannot be undone.
    </div>
    <div class="card mb-4">
        <div class="card-header">
            Item Details
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>ID:</strong> {$item->getId()}</li>
                <li class="list-group-item"><strong>Name:</strong> {$item->getName()|escape}</li>
                <li class="list-group-item"><strong>Type:</strong> {$item->getType()|escape}</li>
                <li class="list-group-item"><strong>Value:</strong> {$item->getValue()} gold</li>
            </ul>
        </div>
    </div>
    <form action="index.php?page=deleteItemConfirm" method="POST" class="d-inline">
        <input type="hidden" name="id" value="{$item->getId()}">
        <button type="submit" class="btn btn-danger">Yes, Delete Item</button>
        <a href="index.php?page=listItems" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
{/block}