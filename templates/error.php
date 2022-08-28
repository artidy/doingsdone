<?php
/**
 * Шаблон ошибки
 *
 * @var $field_name string - поле с ошибками
 * @var $errors array - массив описания ошибок
 */
?>
<?php if (isset($errors[$field_name])):
    foreach ($errors[$field_name] as $error): ?>
        <p class="form__message"><?=$error?></p>
    <?php endforeach;
endif; ?>
