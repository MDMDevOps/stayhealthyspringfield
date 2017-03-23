<?php if( !empty( $field['label'] ) ) : ?>
    <label for="<?php echo $field['field_id']; ?>"><?php echo $field['label']; ?></label>
<?php endif; ?>

<input type="text" class="<?php echo esc_attr( $field['field_class'] ); ?>"  id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" placeholder="<?php echo $field['placeholder']; ?>">

<?php if( !empty( $field['description'] ) ) : ?>
    <p class="description"><?php echo $field['description'] ?></p>
<?php endif; ?>