<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="Sparks-Hub Private API Documentation",
 * version="1.0.0",
 * )
        * @OA\Post(
        * path="/api/v1/user/register",
        * operationId="Register",
        * tags={"User Authentication"},
        * summary="User Register",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"first_name","last_name","email","phone_number", "password", "password_confirmation"},
        *               @OA\Property(property="first_name", type="text",example="Jane"),
        *               @OA\Property(property="last_name", type="text",example="Doe"),
        *               @OA\Property(property="email", type="text", example="example@gmail.com"),
        *               @OA\Property(property="phone_number", type="text", example="+233555555555"),
        *               @OA\Property(property="password", type="password",example="********"),
        *               @OA\Property(property="password_confirmation", type="password", example="**************")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Register Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Welcome. You have now successfully signed up for Sparks-Hub"),
        *              @OA\Property(property="user",type="object", example="{id:1,.....}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, registration encountered a problem. please try again later"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),           *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),

        *),
        * @OA\Post(
        * path="/api/v1/user/login",
        * operationId="UserLogin",
        * tags={"User Authentication"},
        * summary="User Login",
        * description="User Login here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="text", example="example@gmail.com"),
        *               @OA\Property(property="password", type="password",example="********"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You have successfully signed in to your account"),
        *              @OA\Property(property="user",type="object", example="{id:1,.....}"),
        *              @OA\Property(property="token",type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, login encountered a problem. please try again later"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=403,
        *          description="Wrong credentials",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, Wrong Credentials"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
        * @OA\Post(
        * path="/api/v1/user/verify_email",
        * operationId="UserEmailVerification",
        * tags={"User Authentication"},
        * summary="User Email Verification",
        * description="This API aids the newly registered user to verify their email",
        *     @OA\Parameter(
        *         name="Bearer Token",
        *         in="header",
        *         description="Set the user login token",
        *         @OA\Schema(
        *             type="string"
        *         )
        *     ),
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"code",},
        *               @OA\Property(property="code", type="text", example="1234"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Email verification successful",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You have successfully signed in to your account"),
        *              @OA\Property(property="user",type="object", example="{id:1,.....}"),
        *              @OA\Property(property="token",type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error when a user has already verified their email",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, Your email has already been verified!!"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                * @OA\Post(
        * path="/api/v1/user/resend_email_code",
        * operationId="UserEmailVerificationCodeResend",
        * tags={"User Authentication"},
        * summary="User email verification code resend",
        * description="This API is used to a registered user trying to verify their email request for a  new email verification code to be sent to their email",
          *     @OA\Parameter(
        *         name="Bearer Token",
        *         in="header",
        *         description="Set the user login token",
        *         @OA\Schema(
        *             type="string"
        *         )
        *     ),
        *      @OA\Response(
        *          response=200,
        *          description="User successfully reset their code",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Your verification code has been sent to your email."),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
        * @OA\Post(
        * path="/api/v1/user/reset/password/email_check",
        * operationId="UserPasswordResetEmailCheck",
        * tags={"User Authentication"},
        * summary="User email check during password reset",
        * description="This API is used to check the validity of a users email before they are allowed to reset their password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email",},
        *               @OA\Property(property="email", type="text", example="example@gmail.com"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Email successfully checked for its existence in the list of registered users emails",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Your password reset code has been sent to your email."),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                        * @OA\Post(
        * path="/api/v1/user/reset/password",
        * operationId="UserPasswordReset",
        * tags={"User Authentication"},
        * summary="User password reset",
        * description="This API is used to allow the registered users reset their password in the event of a forgot password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email","password","password_reset_code"},
        *               @OA\Property(property="email", type="string", example="example@gmail.com"),
        *               @OA\Property(property="password", type="string", example="*************"),
        *               @OA\Property(property="password_reset_code", type="string", example="2342"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="User successfully reset their code",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You have successfully updated your password"),
        *              @OA\Property(property="user",type="object", example="{user:[id:1,...]}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
        * @OA\Post(
        * path="/api/v1/user/resend/password_reset_code",
        * operationId="UserPasswordResetCodeResend",
        * tags={"User Authentication"},
        * summary="User password reset code resend",
        * description="This API is used to a registered user trying to reset their password request for a new password reset code to be sent to their email",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email","password","password_reset_code"},
        *               @OA\Property(property="email", type="string", example="example@gmail.com"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="User successfully reset their code",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Your password reset code has been sent to your email"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),

        * @OA\Post(
        * path="/api/v1/admin/add_admin",
        * operationId="RegisterNewAdmin",
        * tags={"Admin Authentication"},
        * summary="Add new Admin",
        * description="This API is to enable a super admin add other admins to the application",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"first_name","last_name","email","phone_number", "role"},
        *               @OA\Property(property="first_name", type="string",example="Jane"),
        *               @OA\Property(property="last_name", type="string",example="Doe"),
        *               @OA\Property(property="email", type="string", example="example@gmail.com"),
        *               @OA\Property(property="phone_number", type="string", example="+233555555555"),
        *               @OA\Property(property="role", type="string",example="super_admin/admin"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="New Admin Added Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You have successfully added a new User with super_admin/admin privileges"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="When the user trying to add another user is not a super admin",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, only a super admin can add new admins/super-admins"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),           *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
         * @OA\Post(
        * path="/api/v1/admin/login",
        * operationId="AdminLogin",
        * tags={"Admin Authentication"},
        * summary="Admin Login",
        * description="This API allows the super Login here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="text", example="example@gmail.com"),
        *               @OA\Property(property="password", type="password",example="********"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You have successfully signed in to your account"),
        *              @OA\Property(property="admin",type="object", example="{id:1,.....}"),
        *              @OA\Property(property="token",type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, login encountered a problem. please try again later"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="request payload error"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User not found",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=403,
        *          description="Wrong credentials",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, Wrong Credentials"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
