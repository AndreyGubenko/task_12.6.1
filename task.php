<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullNameFromParts ($a, $b, $c) {
    return $a." ". $b." ". $c;
}

echo getFullNameFromParts("Иванов", "Иван", "Иванович");

function getPartsFromFullName ($name) {
    return explode(" ", $name);
}

function getShortName($fullName) {
    $parts = getPartsFromFullName($fullName);
    $lastName = array_shift($parts);
    $firstName = array_shift($parts);
    $shortLastName = mb_substr($lastName, 0, 1) . '.';
    return $firstName . ' ' . $shortLastName;
}


function getGenderFromName($fullName) {
    $parts = getPartsFromFullName($fullName);
    
    $genderSum = 0; 
    $patronymic = end($parts);
    if (mb_substr($patronymic, -3) === 'вна') {
        $genderSum--;
    } elseif (mb_substr($patronymic, -3) === 'ич') {
        $genderSum++;
    }
    
    $firstName = $parts[1];
    if (mb_substr($firstName, -1) === 'а') {
        $genderSum--;
    } elseif (mb_substr($firstName, -1) === 'й' || mb_substr($firstName, -2) === 'нь') {
        $genderSum++;
    }
    
    $lastName = $parts[0];
    if (mb_substr($lastName, -2) === 'ва') {
        $genderSum--;
    } elseif (mb_substr($lastName, -1) === 'в') {
        $genderSum++;
    }
    
    if ($genderSum > 0) {
        return 1; 
    } elseif ($genderSum < 0) {
        return -1; 
    } else {
        return 0;
    }
}

function getGenderDescription($personsArray) {
    $total = count($personsArray);
    $maleCount = 0;
    $femaleCount = 0;
    $undefinedCount = 0;
    
    foreach ($personsArray as $person) {
        $gender = getGenderFromName($person['fullname']);
        if ($gender === 1) {
            $maleCount++;
        } elseif ($gender === -1) {
            $femaleCount++;
        } else {
            $undefinedCount++;
        }
    }
    
    $malePercentage = round(($maleCount / $total) * 100, 1);
    $femalePercentage = round(($femaleCount / $total) * 100, 1);
    $undefinedPercentage = round(($undefinedCount / $total) * 100, 1);
    
    return [
        'Мужчины' => $malePercentage . '%',
        'Женщины' => $femalePercentage . '%',
        'Не удалось определить' => $undefinedPercentage . '%',
    ];
}

function getPerfectPartner($lastName, $firstName, $patronymic, $personsArray) {
    $fullName = getFullNameFromParts($lastName, $firstName, $patronymic);
    
    $gender = getGenderFromName($fullName);
    
    $filteredArray = array_filter($personsArray, function($person) use ($gender) {
        $personGender = getGenderFromName($person['fullname']);
        return $personGender !== $gender;
    });
    
    $randomPerson = $filteredArray[array_rand($filteredArray)];
    
    $compatibilityPercentage = mt_rand(5000, 10000) / 100;
    
    return "{$lastName} {$firstName[0]}. + {$randomPerson['fullname']} =\n Идеально на {$compatibilityPercentage}%";
}