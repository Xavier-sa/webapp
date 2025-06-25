<?php

const CURSO = 'Desenvolvimento';
const TURMA = '146';
const INSTITUICAO = 'Senac';

$nome = "Xavier";
$idade = 28;
$altura = 1.80;
$profissao = "Programador";
$habilidades = [
    'PHP',
    'JavaScript',
    'HTML',
    'CSS',
    'SQL'
];

echo "<h1>Curso: " . CURSO . "</h1>";
echo "<h2>Turma: " . TURMA . "</h2>";       
echo "<h3>Instituição: " . INSTITUICAO . "</h3>";
echo '<h4>Nome: ' . $nome . '</h4>';
echo '<p>Idade: ' . $idade . '</p>';
echo '<p>'. $altura .'</p>';
echo '<h2>'. $profissao .'<h2>';
echo '<h2>'. $habilidades .'<h2>';