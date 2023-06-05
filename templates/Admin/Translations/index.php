<div class="container">
    <div class="content">
        <div class="row mb-2">
            <div class="column">
                <?php if (empty($translations)): ?>
                    <p class="message message-warning mb-2"><?= __('No translations found in the database') ?></p>
                    <?= $this->Html->link(__('Import translations from files'), 
                        [
                            'action'        => 'importFiles',
                        ], 
                        [
                            'class' => 'button button-danger button-outline'
                        ]) ?>
                <?php else: ?>
                    <h3><?= __('Found {0} translations in the database', count($translations)) ?></h3>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <?= $this->Html->link(__('Generate ES file'), [
                        'action'        => 'generate/es',
                    ], ['class' => 'button button-primary']) ?>
                <?= $this->Html->link(__('Generate RU file'), [
                        'action'        => 'generate/ru',
                    ], ['class' => 'button button-primary']) ?>
            </div>
        </div>
    </div>

    <?php if (!empty($translations)) :?>
        <div class="content">
            <div class="row mb-1">
                <div class="column">
                    <?= $this->Html->link(__('Add translation'), ['action' => 'add'], ['class' => 'button button-primary button-sm']) ?>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= __('English') ?></th>
                                <th><?= __('Spanish') ?></th>
                                <th><?= __('Russian') ?></th>
                                <th><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($translations as $translation): ?>
                                <?php (empty($translation->es) || empty($translation->ru)) ? $class = 'danger' : $class = 'success' ?>
                                <tr class="<?= $class ?>">
                                    <td><?= h($translation->en) ?></td>
                                    <td><?= h($translation->es) ?></td>
                                    <td><?= h($translation->ru) ?></td>
                                    <td>
                                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $translation->id], ['class' => 'button button-primary button-sm button-outline']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>