<?php
/**
 * Email Template Preview
 * Test page to preview custom email headers and footers
 */

// Include WordPress
$wp_path = realpath(__DIR__ . '/../../../../');
if (file_exists($wp_path . '/wp-config.php')) {
    require_once $wp_path . '/wp-config.php';
}

// Check if user is admin
if (!current_user_can('administrator')) {
    wp_die('Access denied. Admin privileges required.');
}

// Include our email functions
require_once __DIR__ . '/email-schema.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrilux Email Template Preview</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .preview-container { max-width: 800px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .preview-header { background: #2d8f47; color: white; padding: 20px; text-align: center; }
        .preview-content { padding: 30px; }
        .email-preview { border: 1px solid #ddd; border-radius: 4px; overflow: hidden; margin-bottom: 30px; }
        .schema-preview { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin-bottom: 20px; }
        .schema-title { color: #495057; font-weight: bold; margin-bottom: 10px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        .test-button { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .test-button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1>🧪 Nutrilux Email & Schema Preview</h1>
            <p>Preview custom email templates and JSON-LD structured data</p>
        </div>
        
        <div class="preview-content">
            <h2>📧 Email Template Preview</h2>
            
            <div class="email-preview">
                <div style="background: #f9f9f9; padding: 20px; border-bottom: 1px solid #ddd;">
                    <strong>Sample: Customer Order Processing Email</strong>
                </div>
                
                <div style="padding: 20px;">
                    <!-- Simulate email header -->
                    <?php
                    // Create mock email object
                    $mock_email = new stdClass();
                    $mock_email->id = 'customer_processing_order';
                    
                    echo nutrilux_custom_email_header('Potvrda narudžbe #123 (Pouzeće)', $mock_email);
                    ?>
                    
                    <div style="max-width: 600px; margin: 0 auto; padding: 0 20px;">
                        <p>Pozdrav Marko Petrović,</p>
                        
                        <p>Hvala vam na narudžbi! Ova email potvrđuje da smo primili vašu narudžbu i da je trenutno u obradi.</p>
                        
                        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Proizvod</th>
                                <th style="padding: 12px; text-align: right; border-bottom: 1px solid #ddd;">Ukupno</th>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">Cijelo jaje u prahu × 2</td>
                                <td style="padding: 12px; text-align: right; border-bottom: 1px solid #ddd;">17.80 BAM</td>
                            </tr>
                            <tr style="background: #f8f9fa;">
                                <td style="padding: 12px; font-weight: bold;">Ukupno</td>
                                <td style="padding: 12px; text-align: right; font-weight: bold;">17.80 BAM</td>
                            </tr>
                        </table>
                        
                        <div style="margin: 20px 0; padding: 15px; background: #e8f5e8; border-left: 4px solid #28a745;">
                            <strong>Napomena:</strong> Plaćate gotovinom kuriru pri preuzimanju. Ako trebate izmjenu podataka javite se na info@nutrilux.ba
                        </div>
                        
                        <p>S poštovanjem,<br>Nutrilux tim</p>
                    </div>
                    
                    <!-- Email footer -->
                    <?php nutrilux_custom_email_footer($mock_email); ?>
                </div>
            </div>
            
            <h2>🔍 Schema JSON-LD Preview</h2>
            
            <!-- Organization Schema -->
            <div class="schema-preview">
                <div class="schema-title">📋 Organization Schema (Homepage only)</div>
                <pre><?php
                $org_schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'Nutrilux',
                    'url' => home_url(),
                    'logo' => home_url('/wp-content/themes/theme-nutrilux/assets/images/logo.png'),
                    'description' => 'Premium proizvođač dehidriranih jaja u prahu i sportskih suplemenata u Bosni i Hercegovini.',
                    'contactPoint' => array(
                        '@type' => 'ContactPoint',
                        'email' => 'info@nutrilux.ba',
                        'contactType' => 'customer support',
                        'areaServed' => 'BA',
                        'availableLanguage' => 'bs'
                    ),
                    'address' => array(
                        '@type' => 'PostalAddress',
                        'addressCountry' => 'BA',
                        'addressRegion' => 'Sarajevo'
                    )
                );
                echo json_encode($org_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                ?></pre>
            </div>
            
            <!-- Product Schema -->
            <div class="schema-preview">
                <div class="schema-title">🥚 Product Schema (Single product pages only)</div>
                <pre><?php
                $product_schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => 'Cijelo jaje u prahu',
                    'description' => 'Dehidrirano cijelo jaje (bjelance i žumance) bez dodataka. Idealno za pekarstvo, slastičarstvo i kampovanje.',
                    'url' => home_url('/product/cijelo-jaje-u-prahu/'),
                    'image' => home_url('/wp-content/uploads/2024/01/cijelo-jaje.jpg'),
                    'brand' => array(
                        '@type' => 'Brand',
                        'name' => 'Nutrilux'
                    ),
                    'manufacturer' => array(
                        '@type' => 'Organization',
                        'name' => 'Nutrilux'
                    ),
                    'offers' => array(
                        '@type' => 'Offer',
                        'price' => '8.90',
                        'priceCurrency' => 'BAM',
                        'availability' => 'https://schema.org/InStock',
                        'seller' => array(
                            '@type' => 'Organization',
                            'name' => 'Nutrilux'
                        )
                    ),
                    'nutrition' => array(
                        '@type' => 'NutritionInformation',
                        'calories' => '560 kcal',
                        'proteinContent' => '48 g',
                        'fatContent' => '37 g',
                        'carbohydrateContent' => '5 g'
                    ),
                    'sku' => 'NTX-001',
                    'category' => 'Dehidrirani proizvodi'
                );
                echo json_encode($product_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                ?></pre>
            </div>
            
            <!-- Website Schema -->
            <div class="schema-preview">
                <div class="schema-title">🌐 Website Schema (Homepage only)</div>
                <pre><?php
                $website_schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => get_bloginfo('name'),
                    'url' => home_url(),
                    'description' => get_bloginfo('description'),
                    'inLanguage' => 'bs',
                    'potentialAction' => array(
                        '@type' => 'SearchAction',
                        'target' => array(
                            '@type' => 'EntryPoint',
                            'urlTemplate' => home_url('/?s={search_term_string}')
                        ),
                        'query-input' => 'required name=search_term_string'
                    )
                );
                echo json_encode($website_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                ?></pre>
            </div>
            
            <h2>🧪 Testing Instructions</h2>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 15px; margin: 20px 0;">
                <strong>📧 Email Testing:</strong>
                <ol>
                    <li>Place a test order to trigger customer emails</li>
                    <li>Check admin email for new order notifications</li>
                    <li>Verify custom header and footer appear correctly</li>
                    <li>Test in different email clients (Gmail, Outlook, etc.)</li>
                </ol>
            </div>
            
            <div style="background: #d1ecf1; border: 1px solid #b8daff; border-radius: 4px; padding: 15px; margin: 20px 0;">
                <strong>🔍 Schema Testing:</strong>
                <ol>
                    <li>Visit homepage and view page source (Ctrl+U)</li>
                    <li>Search for "application/ld+json" to find Organization schema</li>
                    <li>Visit single product page and check for Product schema</li>
                    <li>Use <a href="https://search.google.com/test/rich-results" target="_blank">Google Rich Results Test</a></li>
                    <li>Validate JSON at <a href="https://jsonlint.com/" target="_blank">JSONLint.com</a></li>
                </ol>
            </div>
            
            <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; padding: 15px; margin: 20px 0;">
                <strong>✅ Features Implemented:</strong>
                <ul>
                    <li>✅ Custom email header with Nutrilux branding</li>
                    <li>✅ Custom email footer with tagline and contact info</li>
                    <li>✅ Organization schema (homepage only)</li>
                    <li>✅ Product schema with nutrition info (single products only)</li>
                    <li>✅ Website schema with search functionality</li>
                    <li>✅ Breadcrumb schema for navigation</li>
                    <li>✅ Duplicate prevention system</li>
                    <li>✅ Valid JSON output (no trailing commas)</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="<?php echo home_url(); ?>" class="test-button">🏠 Test Homepage Schema</a>
                <a href="<?php echo home_url('/shop/'); ?>" class="test-button">🛍️ Test Product Schema</a>
                <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=email'); ?>" class="test-button">📧 Email Settings</a>
            </div>
        </div>
    </div>
    
    <script>
    // Add syntax highlighting to JSON
    document.addEventListener('DOMContentLoaded', function() {
        const preElements = document.querySelectorAll('pre');
        preElements.forEach(pre => {
            pre.style.fontSize = '12px';
            pre.style.lineHeight = '1.4';
            pre.style.maxHeight = '300px';
            pre.style.overflow = 'auto';
        });
    });
    </script>
</body>
</html>
