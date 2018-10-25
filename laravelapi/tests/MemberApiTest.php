<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemberApiTest extends TestCase
{
    use MakeMemberTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMember()
    {
        $member = $this->fakeMemberData();
        $this->json('POST', '/api/v1/members', $member);

        $this->assertApiResponse($member);
    }

    /**
     * @test
     */
    public function testReadMember()
    {
        $member = $this->makeMember();
        $this->json('GET', '/api/v1/members/'.$member->id);

        $this->assertApiResponse($member->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMember()
    {
        $member = $this->makeMember();
        $editedMember = $this->fakeMemberData();

        $this->json('PUT', '/api/v1/members/'.$member->id, $editedMember);

        $this->assertApiResponse($editedMember);
    }

    /**
     * @test
     */
    public function testDeleteMember()
    {
        $member = $this->makeMember();
        $this->json('DELETE', '/api/v1/members/'.$member->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/members/'.$member->id);

        $this->assertResponseStatus(404);
    }
}
