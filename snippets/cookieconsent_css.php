<?php if (option('zephir.cookieconsent.cdn')): ?>
    <?= css('https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.9.1/dist/cookieconsent.css') ?>
<?php else: ?>
    <?= css('/media/plugins/zephir/cookieconsent/index.css') ?>
<?php endif; ?>