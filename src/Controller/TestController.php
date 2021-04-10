<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;


/**
 * Description of OutletProductController
 *
 * @author qru19
 */
class TestController {
    public function number(): Response
    {
        $number = random_int(0, 100);
        return new Response(
                '<html><body>Random numero: '.$number.'</body></html>'
        );
    }
}
