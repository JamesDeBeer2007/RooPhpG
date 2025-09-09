{extends file='layout.tpl'}

{block name="content"}
<div class="card my-4">
    <div class="card-header">
        <h2 class="mb-0">Character List</h2>
    </div>
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Health</th>
                    <th>Attack</th>
                    <th>Defense</th>
                    <th>Range</th>
                    <th>Rage</th>
                    <th>Mana</th>
                    <th>Energy</th>
                    <th>Spirit</th>
                    <th>Shield</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$characters item=character}
                <tr>
                    <td>{$character->getName()}</td>
                    <td>{$character->getRole()}</td>
                    <td>{$character->getHealth()}</td>
                    <td>{$character->getAttack()}</td>
                    <td>{$character->getDefense()}</td>
                    <td>{$character->getRange()}</td>
                    <td>
                        {if $character->getRole() == 'Warrior' && $character->getRage() !== null}
                            {$character->getRage()}
                        {else}
                            -
                        {/if}
                    </td>
                    <td>
                        {if $character->getRole() == 'Mage' && $character->getMana() !== null}
                            {$character->getMana()}
                        {else}
                            -
                        {/if}

                    </td>
                    <td>
                        {if $character->getRole() == 'Rogue' && $character->getEnergy() !== null}
                            {$character->getEnergy()}
                        {else}
                            -
                        {/if}
                    </td>
                    <td>
                        {if $character->getRole() == 'Healer' && $character->getSpirit() !== null}
                            {$character->getSpirit()}
                        {else}
                            -
                        {/if}
                    </td>
                    <td>
                        {if $character->getRole() == 'Tank' && $character->getShield() !== null}
                            {$character->getShield()}
                        {else}
                            -
                        {/if}
                    </td>
                    <td>
                        <a href="index.php?page=viewCharacter&name={$character->getName()}" class="btn btn-sm btn-primary">View</a>
                        <a href="index.php?page=deleteCharacter&name={$character->getName()}" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{/block}