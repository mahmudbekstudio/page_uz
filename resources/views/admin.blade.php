<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin panel</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900%7CRoboto+Mono:500%7CMaterial+Icons" rel="stylesheet">
    <link href="/css/admin/styles.css" rel="stylesheet">
</head>
<body>
<div class="admin-app">
    <App
        :website="{{websiteData(true)}}"
        :type-navigation="{{typeNavigation(true)}}"
    ></App>
</div>
<script src="/js/admin/main.js?t={{ time() }}"></script>
</body>
</html>
