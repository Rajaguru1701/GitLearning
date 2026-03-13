<?php
// API: Shopping Cart (session-based)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
session_start();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        $id    = (int)($_POST['product_id'] ?? 0);
        $name  = strip_tags($_POST['name']  ?? '');
        $price = (float)($_POST['price']    ?? 0);
        $size  = strip_tags($_POST['size']  ?? '');
        $color = strip_tags($_POST['color'] ?? '');
        $image = strip_tags($_POST['image'] ?? '');
        $qty   = (int)($_POST['qty']        ?? 1);

        if (!$id || !$name || $price <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product data']); exit;
        }

        $key = $id . '_' . $size . '_' . $color;
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$key] = compact('id','name','price','size','color','image','qty');
        }
        echo json_encode(['success' => true, 'message' => 'Added to cart', 'cart_count' => array_sum(array_column($_SESSION['cart'],'qty'))]);
        break;

    case 'remove':
        $key = $_POST['key'] ?? '';
        unset($_SESSION['cart'][$key]);
        echo json_encode(['success' => true, 'cart' => array_values($_SESSION['cart'])]);
        break;

    case 'update':
        $key = $_POST['key'] ?? '';
        $qty = (int)($_POST['qty'] ?? 1);
        if (isset($_SESSION['cart'][$key])) {
            if ($qty <= 0) { unset($_SESSION['cart'][$key]); }
            else { $_SESSION['cart'][$key]['qty'] = $qty; }
        }
        echo json_encode(['success' => true, 'cart' => array_values($_SESSION['cart'])]);
        break;

    case 'get':
        $total = 0;
        foreach ($_SESSION['cart'] as $item) { $total += $item['price'] * $item['qty']; }
        echo json_encode([
            'success'    => true,
            'cart'       => $_SESSION['cart'],
            'cart_count' => array_sum(array_column($_SESSION['cart'],'qty')),
            'total'      => $total
        ]);
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'message' => 'Cart cleared']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
}
?>
