<?php
header('Content-Type: application/json');

include_once 'admin/connect.php';
include_once 'admin/function.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 6;
$offset = ($page - 1) * $limit;

$total = 0;
$stmt_count = $connect->prepare("SELECT COUNT(*) as total FROM loveimg");
if ($stmt_count) {
    $stmt_count->execute();
    $totalRes = $stmt_count->get_result();
    $totalRow = $totalRes->fetch_assoc();
    $total = isset($totalRow['total']) ? intval($totalRow['total']) : 0;
    $stmt_count->close();
}

$data = [];
$stmt = $connect->prepare("SELECT imgUrl, imgDatd, imgText FROM loveimg ORDER BY id DESC LIMIT ?, ?");
if ($stmt) {
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'img' => $row['imgUrl'],
            'date' => $row['imgDatd'],
            'text' => $row['imgText']
        ];
    }
    $stmt->close();
}

echo json_encode([
    'code' => 200,
    'data' => $data,
    'total' => $total,
    'page' => $page,
    'limit' => $limit
]);