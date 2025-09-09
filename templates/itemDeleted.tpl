{extends file="layout.tpl"}

{block name="content"}
<div class="container mt-5">
    <div class="alert alert-success" role="alert">
        Item has been successfully deleted!
    </div>
    <div class="card mb-4">
        <div class="card-header">
            Deleted Item Details
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
    <a href="index.php?page=listItems" class="btn btn-primary">Back to Item List</a>
    <a href="index.php?page=createItem" class="btn btn-success ms-2">Create New Item</a>
</div>
{/block}