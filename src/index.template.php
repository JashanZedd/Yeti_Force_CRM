<!DOCTYPE html>
<html>

<head>
  <title><%= htmlWebpackPlugin.options.productName %></title>

  <meta charset="utf-8">
  <meta name="description" content="<%= htmlWebpackPlugin.options.productDescription %>">
  <meta name="format-detection" content="telephone=no">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="viewport"
    content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width<% if (htmlWebpackPlugin.options.ctx.mode.cordova) { %>, viewport-fit=cover<% } %>">

  <link rel="icon" href="statics/quasar-logo.png" type="image/x-icon">
  <link rel="icon" type="image/png" sizes="32x32" href="statics/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="statics/icons/favicon-16x16.png">
  <script>window.CONFIG = <?php echo json_encode($config); ?>;</script>
  <script src="<%= htmlWebpackPlugin.options.modulesFile %>"></script>
</head>

<body>
  <!-- DO NOT touch the following DIV -->
  <div id="q-app"></div>
</body>

</html>
