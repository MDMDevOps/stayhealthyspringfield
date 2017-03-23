<?php if( !empty( $field['label'] ) ) : ?>
    <label for="<?php echo $field['field_id']; ?>"><?php echo $field['label']; ?></label>
<?php endif; ?>


<textarea cols="30" rows="10" class="<?php echo esc_attr( $field['field_class'] ); ?>"  id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>"><?php echo $field['value']; ?></textarea>

<?php if( !empty( $field['description'] ) ) : ?>
    <span class="description"><?php echo $field['description'] ?></span>
<?php endif; ?>