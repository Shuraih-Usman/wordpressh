<?php

function render_ebook_meta_box($post) {
    global $wpdb;

    // Get stored values (comma-separated)
    $authors = get_post_meta($post->ID, 'author', true);
    $compilers = get_post_meta($post->ID, 'compiler', true);
    $groups = get_post_meta($post->ID, 'groups', true);
    $category_id = get_post_meta($post->ID, 'cid', true);

    // Fetch existing items for selection
    $existing_authors = $wpdb->get_col("SELECT name FROM author");
    $existing_compilers = $wpdb->get_col("SELECT name FROM compiler");
    $existing_groups = $wpdb->get_col("SELECT name FROM groups");
    $existing_cat = $wpdb->get_results("SELECT id, name FROM category", ARRAY_A);

    ?>

  <div class="container-fluid">
        <div class="row">
            <!-- Authors -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ebook_author" class="form-label"><?php _e('Author(s):', 'textdomain'); ?></label>
                    <select id="ebook_author" name="ebook_author[]" class="form-select select2" multiple="multiple">
                        <?php foreach ($existing_authors as $author) {
                            $selected = in_array($author, explode(',', $authors)) ? 'selected' : '';
                            echo "<option value='$author' $selected>$author</option>";
                        } ?>
                    </select>
                </div>
            </div>

            <!-- Compilers -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ebook_compiler" class="form-label"><?php _e('Compiler(s):', 'textdomain'); ?></label>
                    <select id="ebook_compiler" name="ebook_compiler[]" class="form-select select2" multiple="multiple">
                        <?php foreach ($existing_compilers as $compiler) {
                            $selected = in_array($compiler, explode(',', $compilers)) ? 'selected' : '';
                            echo "<option value='$compiler' $selected>$compiler</option>";
                        } ?>
                    </select>
                </div>
            </div>

            <!-- Groups -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ebook_group" class="form-label"><?php _e('Group(s):', 'textdomain'); ?></label>
                    <select id="ebook_group" name="ebook_group[]" class="form-select select2" multiple="multiple">
                    <?php foreach ($existing_groups as $group) {
                            $selected = in_array($group, explode(',', $groups)) ? 'selected' : '';
                            echo "<option value='$group' $selected>$group</option>";
                        } ?>
                    </select>
                </div>
            </div>

             <!-- Groups -->
             <div class="col-md-6">
                <div class="mb-3 ">
                    <label for="ebook_category" class="form-label"><?php _e('Categories:', 'textdomain'); ?></label>
                    <select id="ebook_category" name="ebook_category" class="form-select select1">
                    <?php foreach ($existing_cat as $group) {
                            $selected = $group['id'] == $category_id ? 'selected' : '';
                            echo "<option value='{$group['id']}' $selected>{$group['name']}</option>";
                        } ?>
                    </select>
                </div>
            </div>

            
            <div class="col-md-12 mb-2">
                                  <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="" name="phone"/>
                                    <label for="phone">Author contact</label>
            </div>
                                  </div>
        </div>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            $('.select2').select2({
                tags: true,
                tokenSeparators: [',']
            });

            $('.select1').select2();
        });
    </script>
    <?php
}
