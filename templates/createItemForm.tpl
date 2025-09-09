{extends file="layout.tpl"}

{block name="content"}
<div class="row justify-content-center my-5">
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Create Item</h4>
      </div>
      <div class="card-body">
        <form action="index.php?page=saveItem" method="POST">
          <div class="mb-3">
            <label for="itemName" class="form-label">Naam</label>
            <input type="text" class="form-control" id="itemName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="itemType" class="form-label">Type</label>
            <select class="form-select" id="itemType" name="type" required>
              <option value="" disabled selected>Kies een type</option>
              <option value="weapon">Weapon</option>
              <option value="armor">Armor</option>
              <option value="consumable">Consumable</option>
              <option value="misc">Misc</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="itemValue" class="form-label">Waarde</label>
            <input type="number" class="form-control" id="itemValue" name="value" min="0" step="0.01" required>
          </div>
          <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Create Item</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{/block}