<?php

namespace controller;

use model\Mailer;
use model\User;
use thecodeholic\phpmvc\Controller;
use thecodeholic\phpmvc\Request;

require_once 'model\Mailer.php';
require_once 'model\User.php';

class MailerController extends Controller
{
    public function mailerForRegister(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $userModel = new  User();
        $user = $userModel->findUserById($userId);

        $template = file_get_contents(__DIR__ . '/../views/mailForRegister.html');
        $body = str_replace(['{{username}}', '{{email}}'], [$user['username'], $user['email']], $template);

        $mailer = new Mailer();
        $to = $user['email'];
        $subject = 'From ToDoListProject';
        $mailer->sendMail($to, $subject, $body);
        header("Location: /singlePage/" . $user['id']);
        return $this->render('singlePage');
    }
}