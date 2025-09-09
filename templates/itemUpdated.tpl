{extends file="layout.tpl"}

{block name="content"}
<div class="container mt-5">
    <div class="alert alert-success" role="alert">
        Item has been successfully updated!
    </div>
    <div class="card">
        <div class="card-header">
            Updated Item Details
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>ID:</strong> {$item->getId()}</li>
                <li class="list-group-item"><strong>Name:</strong> {$item->getName()|escape}</li>
                <li class="list-group-item"><strong>Type:</strong> {$item->getType()|escape}</li>
                <li class="list-group-item"><strong>Value:</strong> {$item->getValue()} gold</li>
            </ul>
            <a href="index.php?page=listItems" class="btn btn-primary mt-3">Back to Item List</a>
        </div>
    </div>
</div>
{/block}