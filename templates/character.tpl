{extends file='layout.tpl'}

{block name="content"}
    <div class="card my-4" style="max-width: 400px;">
        <div class="card-header">
            <h2 class="mb-0">{$character->getName()}</h2>
        </div>
        <div class="card-body">
            <p class="card-text"><strong>Name:</strong> {$character->getName()}</p>
            <p class="card-text"><strong>Health:</strong> {$character->getHealth()}</p>
            <p class="card-text"><strong>Attack:</strong> {$character->getAttack()}</p>
            <p class="card-text"><strong>Defense:</strong> {$character->getDefense()}</p>
            <p class="card-text"><strong>Role:</strong> {$character->getRole()}</p>
            <p class="card-text"><strong>Range:</strong> {$character->getRange()}</p>
            {if $character->getRole() == 'Warrior' && $character->getRage() !== null}
                <p class="card-text"><strong>Rage:</strong> {$character->getRage()}</p>
            {/if}
            {if $character->getRole() ==  'Mage' && $character->getmana() !== null}
                <p class="card-text"><strong>Mana:</strong> {$character->getMana()}</p>
            {/if}
            {if $character->getRole() == 'Rogue' && $character->getEnergy() !== null}
                <p class="card-text"><strong>Energy:</strong> {$character->getEnergy()}</p>
            {/if}
            {if $character->getRole() == 'Healer' && $character->getSpirit() !== null}
                <p class="card-text"><strong>Spirit:</strong> {$character->getSpirit()}</p>
            {/if}
            {if $character->getRole() == 'Tank' && $character->getShield() !== null}
                <p class="card-text"><strong>Shield:</strong> {$character->getShield()}</p>
            {/if}

            <hr>
            <div class="card-text">
                <strong>Character Summary:</strong> 
                <p>{$character->getSummary()}</p>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="index.php?page=listCharacters" class="btn btn-secondary">Terug naar lijst</a>
        </div>
    </div>
{/block}