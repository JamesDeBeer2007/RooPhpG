{extends file="layout.tpl"}

{block name="content"}
<div class="row justify-content-center my-5">
  <div class="col-md-8">
    <div class="alert alert-success text-center" role="alert">
      Item succesvol aangemaakt!
    </div>
    <div class="card shadow">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0">Item Details</h4>
      </div>
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-sm-3">ID</dt>
          <dd class="col-sm-9">{$item->getId()}</dd>

          <dt class="col-sm-3">Name</dt>
          <dd class="col-sm-9">{$item->getName()}</dd>

          <dt class="col-sm-3">Type</dt>
          <dd class="col-sm-9">{$item->getType()}</dd>

          <dt class="col-sm-3">Value</dt>
          <dd class="col-sm-9">{$item->getValue()} gold</dd>
        </dl>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <a href="index.php?page=createItem" class="btn btn-primary">Create Another Item</a>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
      </div>
    </div>
  </div>
</div>
{/block}