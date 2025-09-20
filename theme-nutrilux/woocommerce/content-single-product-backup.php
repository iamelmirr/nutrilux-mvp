<?php
defined('ABSPATH') || exit;
global $product;
$acf_nutrition = function_exists('get_field') ? get_field('nutritivna_tablica') : false;
$has_acf_nutrition = $acf_nutrition && is_array($acf_nutrition) && count($acf_nutrition);
?>
        <?php if ($has_acf_nutrition): ?>
            <div class="product-nutrition-table">
                <h2>Nutritivne vrijednosti (100g)</h2>
                <table>
                    <tr><th>Parametar</th><th>Vrijednost</th></tr>
                    <?php foreach ($acf_nutrition as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['parametar'] ?? ''); ?></td>
                            <td><?php echo esc_html($row['vrijednost'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>