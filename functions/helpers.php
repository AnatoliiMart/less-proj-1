<?php
session_start();
function clear($data)
{
  return htmlentities(trim($data));
}
function redirect(string $url)
{
  header("Location: $url");
  exit();
}
