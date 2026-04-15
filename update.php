<?php
goto aMLvB;
d8Lfl:
if (!$stmt) {
    echo json_encode(array("\x73\165\143\143\145\x73\x73" => false, "\145\x72\x72\157\x72" => "\x50\x72\x65\x70\x61\162\145\40\146\x61\151\x6c\145\x64\72\x20" . $conn->error));
    die;
}
goto Q8QxL;
ZQ1B6:
$stmt->bind_param($types, ...$values);
goto lF9mF;
RqqYB:
foreach ($_POST as $col => $val) {
    if ($val !== null && $col !== '') {
        $updates[] = "\140{$col}\140\x20\x3d\x20\x3f";
        $values[] = $val;
    }
}
goto S7tJ8;
Q8QxL:
$values[] = $id;
goto Me9Hh;
WcZXf:
$id = intval($_POST["\x69\x64"]);
goto RF4JT;
RF4JT:
unset($_POST["\151\x64"]);
goto PyZCA;
vnDek:
$stmt = $conn->prepare($sql);
goto d8Lfl;
Uxhod:
if (!isset($_POST["\151\144"]) || !is_numeric($_POST["\x69\144"])) {
    echo json_encode(array("\163\x75\143\x63\145\x73\163" => false, "\145\162\162\157\162" => "\111\x6e\166\141\154\151\x64\x20\x49\x44"));
    die;
}
goto WcZXf;
LbuxX:
$conn = new mysqli("\154\x6f\x63\141\154\150\x6f\x73\x74", "\162\157\157\164", '', "\x74\141\151\154\157\162\x5f\x64\142");
goto W34qf;
axXDv:
$updates = array();
goto rUPlQ;
W34qf:
if ($conn->connect_error) {
    echo json_encode(array("\163\165\x63\x63\145\x73\x73" => false, "\x65\162\162\157\x72" => "\x43\x6f\x6e\156\x65\x63\164\151\157\x6e\40\x66\x61\151\154\145\x64"));
    die;
}
goto Uxhod;
S7tJ8:
if (empty($updates)) {
    echo json_encode(array("\163\x75\x63\x63\x65\x73\x73" => false, "\x65\x72\x72\x6f\162" => "\116\x6f\40\166\x61\154\x69\144\40\x64\141\x74\x61\40\x74\157\40\165\x70\144\x61\164\x65"));
    die;
}
goto Od_ec;
FuGmu:
$stmt->close();
goto VeVeB;
VeVeB:
$conn->close();
goto oFYvz;
lF9mF:
if ($stmt->execute()) {
    $stmt2 = $conn->prepare("\123\105\x4c\x45\x43\124\40\x2a\x20\x46\122\x4f\x4d\x20\155\145\141\x73\165\162\145\x6d\x65\x6e\164\x73\40\127\110\105\122\105\x20\151\x64\x3d\77");
    $stmt2->bind_param("\151", $id);
    $stmt2->execute();
    $updated = $stmt2->get_result()->fetch_assoc();
    echo json_encode(array("\x73\165\x63\x63\145\163\x73" => true, "\165\x70\x64\141\164\145\x64\122\x65\143\157\162\x64" => $updated));
} else {
    echo json_encode(array("\163\165\143\143\145\163\163" => false, "\x65\162\x72\x6f\x72" => "\125\160\144\141\x74\145\40\146\141\x69\154\145\x64\72\x20" . $stmt->error));
}
goto FuGmu;
PyZCA:
if (empty($_POST)) {
    echo json_encode(array("\x73\165\143\143\145\x73\163" => false, "\145\162\x72\157\x72" => "\116\x6f\40\144\x61\164\x61\x20\160\162\x6f\166\x69\x64\x65\144"));
    die;
}
goto axXDv;
aMLvB:
header("\x43\157\156\x74\x65\x6e\164\x2d\124\171\160\x65\x3a\40\x61\x70\x70\x6c\151\x63\x61\x74\x69\157\156\x2f\152\x73\x6f\156");
goto LbuxX;
Me9Hh:
$types = str_repeat("\x73", count($values) - 1) . "\x69";
goto ZQ1B6;
Od_ec:
$sql = "\125\120\x44\101\x54\105\40\x6d\145\141\163\x75\162\x65\x6d\145\x6e\164\163\x20\x53\x45\124\40" . implode("\54\x20", $updates) . "\40\127\x48\105\x52\105\x20\151\144\x20\x3d\x20\77";
goto vnDek;
rUPlQ:
$values = array();
goto RqqYB;
oFYvz:
