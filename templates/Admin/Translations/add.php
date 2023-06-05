<div class="row">
    <div class="column-responsive">
        <div class="form content">
            <?= $this->Form->create($translation) ?>
            <div class="row mb-1">
                <legend class="column"><?= __('Add translation') ?></legend>
            </div>
            <fieldset class="row">
                <div class="column">
                    <?= $this->Form->control('en') ?>
                </div>
                <div class="column">
                    <?= $this->Form->control('es') ?>
                </div>
                <div class="column">
                    <?= $this->Form->control('ru') ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
