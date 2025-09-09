{extends file="layout.tpl"}
{block name="content"}
<div class="container my-5">
  <h2 class="mb-4">Character Statistics</h2>

  <div class="mb-4">
    <h4>
      Totaal aantal characters: <span class="badge bg-primary">{$totalCharacters}</span>
    </h4>
  </div>

  <div class="mb-4">
    <h5>Character Types</h5>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Type</th>
          <th>Aantal</th>
        </tr>
      </thead>
      <tbody>
        {foreach $characterTypeCounts as $type => $count}
        <tr>
          <td>{$type}</td>
          <td>{$count}</td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>

  <div class="mb-4">
    <h5>Alle character namen</h5>
    <ul class="list-group">
      {foreach $characterNames as $name}
      <li class="list-group-item">{$name}</li>
      {/foreach}
    </ul>
  </div>

  <div class="mb-4 d-flex gap-2">
    <a href="index.php?page=resetStats" class="btn btn-danger btn-lg">
      <i class="bi bi-arrow-counterclockwise"></i> Reset Statistics
    </a>
    <a href="index.php?page=recalculateStats" class="btn btn-info btn-lg">
      <i class="bi bi-bootstrap-reboot"></i> Recalculate Statistics
    </a>
  </div>
</div>
{/block}