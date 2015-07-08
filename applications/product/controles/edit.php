<?php
    _setView(__FILE__);

    if (check_login_bool()) {
        $productID_name = get_id(2);
        $productID = preg_replace('/[^0-9]/', '', $productID_name);

        $productClass = new product();
        $product = $productClass->get($productID);
        abr('product', $product);

        if (is_array($product) && $product['member_id'] == $_SESSION['member']['member_id']) {
            if ($product['status'] == 'active') {
                _setTitle($langArray['edit'] . ' ' . $product['name']);
                abr('product_description', substr(strip_tags($product['description']), 0, 255));

                require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
                $membersClass = new members();

                $product['member'] = $membersClass->get($product['member_id']);
                $product['description'] = replaceEmoticons($product['description']);
                abr('product', $product);

                if ($product['votes'] > 2) {
                    // Obtenir l'ensemble des notes
                    $productRatings['ratings'] = $productClass->getProductRates($product['id']);
                    // Obtenir le nombre total de notes
                    $productRatings['count'] = count($productClass->getProductRates($product['id']));
                    // Obtenir la moyenne de toutes les notes
                    $productRatings['average'] = 0;
                    foreach ($productRatings['ratings'] as $ratingArray) {
                        $productRatings['average'] += $ratingArray['rate'];
                    }
                    $productRatings['average'] = round($productRatings['average'] / $productRatings['count'], 2);
                    // Obtenir les statistiques pour chaques notes
                    $productRatings['stats'] = array_fill(1, 5, 0);
                    foreach ($productRatings['ratings'] as $ratingArray) {
                        $productRatings['stats'][$ratingArray['rate']]++;
                    }
                    // On déclare la variable
                    abr('productRatings', $productRatings);
                }

                $coffeeCups = round($product['price'] / 2.5);
                abr('coffeeCups', $coffeeCups);

                // Ajout d'un produit dans la collection
                require_once ROOT_PATH . '/applications/product/controles/collection.php';

                // Ajout d'un produit dans les favoris
                require_once ROOT_PATH . '/applications/product/controles/favorites.php';

                if ($product['free_file'] == 'true') {
                    abr('freeFileMessage', langMessageReplace($langArray['free_file_info'], array('URL' => '/members/download/' . $product['id'])));
                }

                require_once ROOT_PATH.'/applications/attributes/modeles/attributes.class.php';
                $attributesClass = new attributes();

                $attributes = $attributesClass->getAll(0, 0, $productClass->attributesWhere);
                abr('attributes', $attributes);

                $attributeCategories = $attributesClass->getAllCategories(0, 0, $productClass->attributeCategoriesWhere);
                abr('attributeCategories', $attributeCategories);

                require_once ROOT_PATH.'/applications/category/modeles/category.class.php';
                $categoriesClass = new category();

                $categories = $categoriesClass->getAll();
                abr('categories', $categories);

                /* Là, ça va plus */
                $first_category = 0;
                foreach($product['categories'] AS $cat) {
                    if (is_array($cat)) {
                        foreach($cat AS $c) {
                            if ($c) {
                                $first_category = $c;
                                break;
                            }
                        }
                    }
                    else {
                        if ($cat) {
                            $first_category = $cat;
                            break;
                        }
                    }
                }

                //$attributes = $attributesClass->getAll(0, 0, $productClass->attributesWhere);$first_category = array_shift($product['categories']);
                $attributes = $attributesClass->getAllWithCategories(" visible = 'true' AND categories LIKE '%," . (int)$first_category . ",%'");
                abr('attributes', $attributes);

                $attributesAside = $attributesClass->getAll(0, 0, $productClass->attributesWhere);
                abr('attributesAside', $attributesAside);

                $attributeCategories = $attributesClass->getAllCategories(0, 0, $productClass->attributeCategoriesWhere);
                abr('attributeCategories', $attributeCategories);

                if (!isset($_POST['category'])) {
                    if(isset($_POST['save'])) {
                        $_POST['category'] = 0;
                    }
                    else {
                        if ($product['categories']) {
                            foreach ($product['categories'] AS $c) {
                                $_POST['category'][] = end($c);
                            }
                        }
                        else {
                            $_POST['category'] = 0;
                        }
                    }
                }

                $allCategories = $categoriesClass->getAllWithChilds(0, " visible = 'true' ");
                $categoriesSelect = $categoriesClass->generateSelect($allCategories, $_POST['category'], $first_category);
                abr('categoriesSelect', $categoriesSelect);
                /*Là, ça va plus*/

                // Vérifier si le fichier est en attente de mise à jour
                if ($productClass->isInUpdateQueue($productID)) {
                    abr('inUpdateQueue', 'yes');
                }

                if (isset($_POST['save'])) {
                    $s = $productClass->edit($productID);

                    if ($s === true) {
                        refresh('/product/' . $productID . '/' . url($product['name']), $langArray['complete_update_product'], 'complete');
                    }

                    else {
                        foreach($s as $e) {
                            $message .= $e;
                        }

                        refresh('/product/' . $productID . '/' . url($product['name']) . '/edit', $message, 'error');
                        // addErrorMessage($message, '', 'error');
                    }

                    $_POST = $product;

                    unset($_POST['tags']);

                    if (isset($product['tags']) && is_array($product['tags'])) {
                        foreach($product['tags'] as $arr) {
                            foreach($arr as $t) {
                                if (!isset($_POST['tags'])) {
                                    $_POST['tags'] = '';
                                }

                                $_POST['tags'] .= $t . ',';
                            }
                        }
                    }
                }

                elseif (isset($_POST['upload'])) {
                    $s = $productClass->edit_upload($productID);

                    if ($s === true) {
                        refresh('/product/' . $productID . '/', $langArray['complete_update_upload_product'], 'complete');
                    }

                    else {
                        foreach($s as $e) {
                            $message .= $e;
                        }

                        addErrorMessage($message, '', 'error');
                    }
                }

                elseif (isset($_POST['delete'])) {
                    $productClass->delete($productID);
                    refresh('/members/dashboard/', $langArray['complete_delete_product'], 'complete');
                }

                else {
                    $_POST = $product;
                    unset($_POST['tags']);

                    if (isset($product['tags']) && is_array($product['tags'])) {
                        foreach($product['tags'] as $arr) {
                            if (!isset($_POST['tags'])) {
                                $_POST['tags'] = '';
                            }

                            $_POST['tags'] .= $arr . ',';
                        }
                    }
                }

                $fileTypes = '';

                foreach($config['upload_ext'] as $ext) {
                    if ($fileTypes != '') {
                        $fileTypes .= ';';
                    }

                    $fileTypes .= '*.'.$ext;
                }

                abr('fileTypes', $fileTypes);

                abr('sessID', session_id());

                require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
                $badges = new badges();

                $badges_data = $badges->getAllFront();

                $member = $_SESSION['member'];
                abr('member', $member);

                $other_badges = array_map('trim', explode(',', $member['badges']));

                $member_badges = array();

                if($member['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
                    if($badges_data['system']['is_exclusive_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo'])) {
                        $member_badges[] = array(
                            'name' => $badges_data['system']['is_exclusive_author']['name'],
                            'name_en' => $badges_data['system']['is_exclusive_author']['name_en'],
                            'photo' => 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo']
                        );
                    }
                }

                $datetime_inscription = new DateTime($member['register_datetime']);
                $datetime_actuelle = new DateTime(date('Y-m-d H:i:s'));

                $interval_dates = $datetime_inscription->diff($datetime_actuelle);

                $interval_dates->format('%y');

                if (isset($badges_data['anciennete']) && is_array($badges_data['anciennete'])) {
                    foreach ($badges_data['anciennete'] AS $k => $v) {
                        list($from, $to) = explode('-', $k);
                        if ($from <= $interval_dates->format('%y') && $to > $interval_dates->format('%y')) {
                            if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
                                $member_badges[] = array(
                                    'name' => $v['name'],
                                    'name_en' => $v['name_en'],
                                    'photo' => 'uploads/badges/' . $v['photo']
                                );
                            }
                            break;
                        }
                    }
                }

                if($member['featured_author'] == 'true' && isset($badges_data['system']['has_been_featured'])) {
                    if($badges_data['system']['has_been_featured']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
                        $member_badges[] = array(
                            'name' => $badges_data['system']['has_been_featured']['name'],
                            'name_en' => $badges_data['system']['has_been_featured']['name_en'],
                            'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
                        );
                    }
                }

                if(isset($member['statuses']['freefile']) && $member['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
                    if($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo'])) {
                        $member_badges[] = array(
                            'name' => $badges_data['system']['has_free_file_month']['name'],
                            'name_en' => $badges_data['system']['has_free_file_month']['name_en'],
                            'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
                        );
                    }
                }

                if(isset($member['statuses']['featured']) && $member['statuses']['featured'] && isset($badges_data['system']['has_had_product_featured'])) {
                    if($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo'])) {
                        $member_badges[] = array(
                            'name' => $badges_data['system']['has_had_product_featured']['name'],
                            'name_en' => $badges_data['system']['has_had_product_featured']['name_en'],
                            'photo' => 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo']
                        );
                    }
                }

                if($member['buy'] && isset($badges_data['buyers']) && is_array($badges_data['buyers'])) {
                    foreach($badges_data['buyers'] AS $k => $v) {
                        list($from, $to) = explode('-', $k);
                        if($from <= $member['buy'] && $to >= $member['buy']) {
                            if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
                                $member_badges[] = array(
                                    'name' => $v['name'],
                                    'name_en' => $v['name_en'],
                                    'photo' => 'uploads/badges/' . $v['photo']
                                );
                            }
                            break;
                        }
                    }
                }

                if($member['sold'] && isset($badges_data['authors']) && is_array($badges_data['authors'])) {
                    foreach($badges_data['authors'] AS $k => $v) {
                        list($from, $to) = explode('-', $k);
                        if($from <= $member['sold'] && $to >= $member['sold']) {
                            if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
                                $member_badges[] = array(
                                    'name' => $v['name'],
                                    'name_en' => $v['name_en'],
                                    'photo' => 'uploads/badges/' . $v['photo']
                                );
                            }
                            break;
                        }
                    }
                }

                if($member['referals'] && isset($badges_data['referrals']) && is_array($badges_data['referrals'])) {
                    foreach($badges_data['referrals'] AS $k => $v) {
                        list($from, $to) = explode('-', $k);
                        if($from <= $member['referals'] && $to >= $member['referals']) {
                            if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
                                $member_badges[] = array(
                                    'name' => $v['name'],
                                    'name_en' => $v['name_en'],
                                    'photo' => 'uploads/badges/' . $v['photo']
                                );
                            }
                            break;
                        }
                    }
                }

                if(isset($badges_data['other']) && is_array($badges_data['other'])) {
                    foreach($badges_data['other'] AS $k => $b) {
                        if(in_array($k, $other_badges) && $b['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $b['photo'])) {
                            $member_badges[] = array(
                                'name' => $b['name'],
                                'name_en' => $b['name_en'],
                                'photo' => 'uploads/badges/' . $b['photo']
                            );
                        }
                    }
                }

                if(isset($member['country']['photo']) && $member['country']['photo'] && file_exists(DATA_SERVER_PATH . '/uploads/countries/' . $member['country']['photo'])) {
                    $member_badges[] = array(
                        'name' => $member['country']['name'],
                        'name_en' => $member['country']['name_en'],
                        'photo' => '/uploads/countries/' . $member['country']['photo']
                    );
                } elseif(isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
                    $member_badges[] = array(
                        'name' => $badges_data['system']['location_global_community']['name'],
                        'name_en' => $badges_data['system']['location_global_community']['name_en'],
                        'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
                    );
                }

                if($member['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
                    if($badges_data['system']['super_elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
                        $member_badges[] = array(
                            'name' => $badges_data['system']['super_elite_author']['name'],
                            'name_en' => $badges_data['system']['super_elite_author']['name_en'],
                            'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
                        );
                    }
                }

                if($member['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
                    if($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
                        $member_badges[] = array(
                            'name' => $badges_data['system']['elite_author']['name'],
                            'name_en' => $badges_data['system']['elite_author']['name_en'],
                            'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
                        );
                    }
                }

                abr('member_badges', $member_badges);
            }

            elseif ($product['status'] == 'deleted') {
                header('Location: http://' . DOMAIN . '/product/' . $productID . '/' . url($product['name']));
            }

            else {
                include_once (ROOT_PATH . '/applications/error/controles/index.php');
            }
        }

        else {
            include_once (ROOT_PATH . '/applications/error/controles/index.php');
        }
    }

    else {
        include_once (ROOT_PATH . '/applications/error/controles/index.php');
    }
?>