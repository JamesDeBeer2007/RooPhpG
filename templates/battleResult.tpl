{extends file='layout.tpl'} 
{block name="content"}
<div class="container my-5">
  <h2 class="mb-4 text-center">Battle Result</h2>
  <div class="row justify-content-center mb-4">
    <!-- ... bestaande character cards ... -->
    <!-- (deze sectie blijft ongewijzigd) -->
  </div>

  <div class="alert alert-info text-center mb-4">
    {if $winner == 'draw'}
    <strong>Het gevecht is geëindigd in een gelijkspel!</strong>
    {else}
    <strong>Winnaar: {$winner}</strong>
    {/if}
  </div>

  <!-- Battle bediening met keuzelijsten voor aanvallen -->
  <div class="text-center my-4">
    <form
      action="index.php?page=battleRound"
      method="post"
      style="display: inline"
    >
      <div class="row justify-content-center mb-3">
        <div class="col-md-5">
          <label for="fighter1Attack" class="form-label"><strong>{$battle->getFighter1()->getName()} aanval:</strong></label>
          <select class="form-select" id="fighter1Attack" name="fighter1attack" {if $battle->getFighter1()->getHealth() <= 0}disabled{/if}>
            <option value="">Normal Attack</option>
            {foreach $battle->getFighter1()->getSpecialAttacks() as $special}
  <option value="{$special}">{$special|capitalize}</option>
{/foreach}
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-center justify-content-center">
          <span class="display-6"></span>
        </div>
        <div class="col-md-5">
          <label for="fighter2Attack" class="form-label"><strong>{$battle->getFighter2()->getName()} aanval:</strong></label>
          <select class="form-select" id="fighter2Attack" name="fighter2Attack" {if $battle->getFighter2()->getHealth() <= 0}disabled{/if}>
            <option value="">Normal Attack</option>
            {foreach $battle->getFighter2()->getSpecialAttacks() as $special}
  <option value="{$special}">{$special|capitalize}</option>
{/foreach}
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-warning btn-lg"
        {if $battle->getFighter1()->getHealth() <= 0 || $battle->getFighter2()->getHealth() <= 0}disabled{/if}>
        Fight Round
      </button>
    </form>
    {if $battle->getFighter1()->getHealth() <= 0 || $battle->getFighter2()->getHealth() <= 0}
    <form
      action="index.php?page=resetHealth"
      method="post"
      style="display: inline"
    >
      <input
        type="hidden"
        name="fighter1"
        value="{$battle->getFighter1()->getName()}"
      />
      <input
        type="hidden"
        name="fighter2"
        value="{$battle->getFighter2()->getName()}"
      />
      <button type="submit" class="btn btn-success btn-lg">Reset Health</button>
    </form>
    {/if}
  </div>

  <!-- Battle log sectie -->
  <div class="card mb-4">
    <div class="card-header">Gevechtsverslag</div>
    <div class="card-body">
      <ul class="mb-0">
        {foreach $battle->getBattleLog() as $regel}
        <li>{$regel nofilter}</li>
        {/foreach}
      </ul>
    </div>
  </div>

  <!-- Navigatieknoppen -->
  <div class="text-center mt-4">
    <a href="index.php?page=battleForm" class="btn btn-secondary me-2"
      >Nieuwe Battle</a
    >
    <a href="index.php?page=listCharacters" class="btn btn-outline-primary"
      >Terug naar Character List</a
    >
  </div>
</div>
{/block}