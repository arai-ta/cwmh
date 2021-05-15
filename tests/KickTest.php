<?php

class KickTest extends TestCase
{
    public function testKicks()
    {
        $kick = new \App\Models\Kick();
        $kick->hook_id = 1;
        $kick->result = "hoge";
        $kick->save();
    }
}
