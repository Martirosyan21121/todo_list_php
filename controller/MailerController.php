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

        $mailer = new Mailer();
        $to = $user['email'];
        $subject = 'From ToDoListProject';
        $body = '<p>Thanks for register</p>';
        $mailer->sendMail($to, $subject, $body);
    }
}