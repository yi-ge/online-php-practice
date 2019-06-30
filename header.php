<?php
//获得当前的脚本网址 
function GetCurUrl()
{
    return $_SERVER["PHP_SELF"];
}

$nowURL = GetCurUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>在线PHP练习</title>
    <link crossorigin="anonymous" integrity="sha384-9Z9AuAj0Xi0z7WFOSgjjow8EnNY9wPNp925TVLlAyWhvZPsf5Ks23Ex0mxIrWJzJ"
          href="https://lib.baomitu.com/normalize/8.0.1/normalize.min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-GQvMoYnQA47ImcAuL/b527e+tU96IK4h/WH1i8Y2oWVSzAne5jXEPr79FFDjbP2O"
          href="https://lib.baomitu.com/pure/1.0.0/base-min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-b92sF+wDNTHrfEtRaYo+EpcA8FUyHOSXrdxKc9XB9kaaX1rSQAgMevW6cYeE5Bdv"
          href="https://lib.baomitu.com/pure/1.0.0/grids-responsive-min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-T7EqrTLlfKxgBXvvj/5Lz2LDKWPwLFsdS/T4MmXC+EjrRKTH9FWtGOn+JHoKWzSJ"
          href="https://lib.baomitu.com/pure/1.0.0/menus-min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-FmOvPjY6YwQXYea5Ja+3CH7+feIm/+HpUXtRUh8g0F7ybli4aDV//h1GzWLDpwHO"
          href="https://lib.baomitu.com/pure/1.0.0/forms-min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-fCgQlRnjKz+VWhXVjN344OnXNV9kmKYH2rCqLz4TmMne2vnO1ykQU4Cz1llu+ud8"
          href="https://lib.baomitu.com/pure/1.0.0/buttons-min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-MIf4KOt3X2OQ4tRxVM7Ygl5hNkkyYabJ8zm0m+TDRw9NYmAkEXXFpst8PVbF2s/9"
          href="https://lib.baomitu.com/pure/1.0.0/tables-min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-rQdIropf4eQBEB9SkNB4xxukYHlkyXJfKYkpVNUQOLizz+d2q0wo7zjVA2XcYSij"
          href="https://lib.baomitu.com/simplemde/1.11.2/simplemde.min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-HNGokaYN28H3qifBLVX5/ob64kuuYiIeQfo/b+M/hzijJeueJbsVEAvglj1qvfuY"
          href="https://lib.baomitu.com/highlight.js/9.13.1/styles/vs.min.css" rel="stylesheet">
    <link crossorigin="anonymous" integrity="sha384-1UXhfqyOyO+W+XsGhiIFwwD3hsaHRz2XDGMle3b8bXPH5+cMsXVShDoHA3AH/y/p"
          href="https://lib.baomitu.com/datatables/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/css/jquery.splitter.css" rel="stylesheet">
    <link href="/css/common.css" rel="stylesheet">
</head>

<body>
<div id="loading">
    <img src="/loading.gif">
</div>
<div id="layout" class="pure-g">
    <div class="header">
        <div class="pure-menu pure-menu-horizontal">
            <span class="pure-menu-heading">PHP练习</span>
            <?php include('menu.php'); ?>
        </div>

        <div class="userinfo">
            <div id="avator"></div>
            <div id="nickname"></div>
            <div id="realname"></div>
        </div>

        <div id="logout">
            <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25">
                <path fill="#d7d9dc"
                      d="M835.669333 554.666667h-473.173333A42.453333 42.453333 0 0 1 320 512a42.666667 42.666667 0 0 1 42.474667-42.666667h473.173333l-161.813333-161.834666a42.666667 42.666667 0 0 1 60.330666-60.330667l234.666667 234.666667a42.666667 42.666667 0 0 1 0 60.330666l-234.666667 234.666667a42.666667 42.666667 0 0 1-60.330666-60.330667L835.669333 554.666667zM554.666667 42.666667a42.666667 42.666667 0 1 1 0 85.333333H149.525333C137.578667 128 128 137.578667 128 149.482667v725.034666C128 886.4 137.6 896 149.525333 896H554.666667a42.666667 42.666667 0 1 1 0 85.333333H149.525333A106.816 106.816 0 0 1 42.666667 874.517333V149.482667A106.773333 106.773333 0 0 1 149.525333 42.666667H554.666667z"></path>
            </svg>
        </div>
    </div>