<?php
require_once __DIR__ . '/../services/InquiriesService.class.php';

Flight::set('inquiries_service', new InquiriesService());

/**
     * @OA\Post(
     *      path="/inquiries",
     *      tags={"inquiries"},
     *      summary="Post an inquiry",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Post an inquiry"
     *      ),
     *     @OA\RequestBody(
     *     description="Inquiry data payload",
     *     @OA\JsonContent(
     *         required={"full_name", "phone_number", "medicine", "message"},
     *         @OA\Property(property="full_name", type="string", example="John Doe"),
     *         @OA\Property(property="phone_number", type="string", example="651879123"),
     *         @OA\Property(property="medicine", type="string", example="SleepWell"),
     *         @OA\Property(property="message", type="string", example="message")
     * )
     * 
     * )
     * )
     */
Flight::route('POST /inquiries', function() { 
    $data = Flight::request()->data->getData();
    $inquiries = Flight::get('inquiries_service')->add_inquiries($data);
    Flight::json(
        $inquiries
    );
});