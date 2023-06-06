<div class="row">
    <div class="column-responsive">
        <div class="form content">
            <?= 
                $this->Form->postLink(
                    __('Delete'), 
                    [
                        'action' => 'delete', $translation->id
                    ], 
                    [
                        'confirm'   => __('Are you sure you want to delete # {0}?', $translation->id),
                        'class'     => 'button button-outline button-sm button-danger'
                    ]
                )
            ?>
            <?= $this->Form->create($translation) ?>
            <?= $this->Form->hidden('id') ?>
            <div class="row mb-1">
                <legend class="column">
                    <span class="mr-1"><?= __('Edit translation') ?></span>
                    
                </legend>
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
