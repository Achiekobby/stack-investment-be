<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="Stacks-Investment-Hub Private API Documentation",
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
        *              @OA\Property(property="message",type="string", example="Welcome. You have now successfully signed up for Stacks-Hub"),
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
                 * @OA\Post(
        * path="/api/v1/admin/change_admin_state/{unique_id of user}",
        * operationId="SuperAdminChangeUserState",
        * tags={"Admin Authentication"},
        * summary="Change the The Role of a A user",
        * description="This API is to enable only a super admin to change the role of other users including other super admins",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"role"},
        *               @OA\Property(property="role", type="string",example="super_admin/admin/regular"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="User role change Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You have successfully changed the role of user"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="When the user trying to add another user is not a super admin",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="Sorry, only a super admin can update the role new admins/super-admins"),
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
        * path="/api/v1/user/project/create",
        * operationId="UserCreateProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="User crowdfunding project creation",
        * description="This API allows the user to create a project and solicit for funds from others users",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"category", "title","description","amount"},
        *               @OA\Property(property="category", type="string", example="agriculture"),
        *               @OA\Property(property="title", type="string",example="Sample Project"),
        *               @OA\Property(property="description", type="text",example="Sample Description"),
        *               @OA\Property(property="amount", type="string",example="100.00"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Added new Crowdfunding project Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Your new project is under review. Please check back within 24 hours for approval status"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
        * @OA\Patch(
        * path="/api/v1/user/project/update/{unique_id_of_crowdfunding_project}",
        * operationId="UserUpdateProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="User crowdfunding project updating",
        * description="This API allows the user to update a project and solicit for funds from others users",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"uuid", "title","description","amount"},
        *               @OA\Property(property="uuid", type="string", example="81a12b6e-1be0...for the logged in"),
        *               @OA\Property(property="title", type="string",example="Sample Project"),
        *               @OA\Property(property="description", type="text",example="Sample Description"),
        *               @OA\Property(property="amount", type="string",example="100.00"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Added new Crowdfunding project Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Your new project is under review. Please check back within 24 hours for approval status"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
                                *),
        * @OA\Get(
        * path="/api/v1/user/project/show/{unique_id_of_crowdfunding_project}",
        * operationId="ExtractDetailsOfProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="User crowdfunding project details extraction using the unique id",
        * description="This API allows the user to extract the details of a crowdfunding project",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved data from the backend about that particular crowdfunding project",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="project:{uuid:5d92d319..., title:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Sorry, Data could not be retrieved"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/user/project/all",
        * operationId="ExtractAllProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="All User crowdfunding projects",
        * description="This API allows the user to extract all the details of every crowdfunding project which has been approved",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved all the crowdfunding projects initiated by the logged in user",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="projects",type="string", example="projects:{uuid:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Bad Request"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/user/project/pending",
        * operationId="ExtractAllPendingProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="All User crowdfunding pending projects",
        * description="This API allows the user to extract all the details of every pending approval crowdfunding project which has been approved",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved all the crowdfunding pending projects initiated by the logged in user",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="pending projects",type="string", example="projects:{uuid:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Bad Request"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Delete(
        * path="/api/v1/user/project/{unique_id of crowdfunding project to be deleted}",
        * operationId="RemoveProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="Remove crowdfunding project",
        * description="This API allows the logged user to remove crowdfunding project which has been initiated by them",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully removed crowdfunding project initiated by the logged in user",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Great, you have successfully removed this project"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="The project you are trying to remove does not belong to you"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/user/project/open",
        * operationId="ExtractAllOpenProjectForDonation",
        * tags={"Crowdfunding Operations"},
        * summary="crowdfunding projects that are active and open to the public for donations",
        * description="This API allows the pulling of all crowdfunding projects which are active and open to the public for donations",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved all the open crowdfunding projects",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="projects",type="string", example="projects:{uuid:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Bad Request"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Post(
        * path="/api/v1/user/organization/create",
        * operationId="UserCreateOrganization",
        * tags={"P2P Organization Operations"},
        * summary="User P2P organization creation",
        * description="This API allows the user to create a P2P organization  and collate funds from every individual who is added of a cycle period",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"title", "description","cycle_period","amount_per_member","start_date"},
        *               @OA\Property(property="title", type="string",example="Sample P2P organization"),
        *               @OA\Property(property="description", type="text",example="Sample Description for the P2P organization"),
        *               @OA\Property(property="cycle_period", type="string",example="Weekly/Monthly/Quarterly/Midyear/Yearly"),
        *               @OA\Property(property="amount_per_member", type="string",example="100.00"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Added new P2P organization  project Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="organization",type="string", example="A JSON Object of the details for the organization"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),


        * @OA\Get(
        * path="/api/v1/user/organization/show/all",
        * operationId="ExtractP2POrganizationProjects",
        * tags={"P2P Organization Operations"},
        * summary="Extract all organizations a logged in user has been part of regardless of whether it has been closed or not",
        * description="Extract all organizations a logged in user has been part of regardless of whether it has been closed or not",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully Extract all organizations a logged in user has been part of regardless of whether it has been closed or not",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="user_organizations",type="string", example="user_organizations:{uuid:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Bad Request"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/user/organization/active/all",
        * operationId="ExtractActiveP2POrganizationProjects",
        * tags={"P2P Organization Operations"},
        * summary="Extract all active P2P organizations a logged in user has been part of regardless of whether it has been closed or not",
        * description="Extract all active P2P organizations a logged in user has been part of regardless of whether it has been closed or not",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully Extract all P2P organizations a logged in user has been part of regardless of whether it has been closed or not",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="user_organizations",type="string", example="user_organizations:{uuid:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Bad Request"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/user/organization/show/{unique_id_of_p2p_organization}",
        * operationId="ExtractDetailsOfP2POrganization",
        * tags={"P2P Organization Operations"},
        * summary="User P2P Organization details extraction using the unique id",
        * description="This API allows the user to extract the details of a P2P organization",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved data from the backend about that particular  P2P organization",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="organization",type="string", example="Details of the particular organization extracted in JSON format"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Patch(
        * path="/api/v1/user/organization/edit/{unique_id of the P2P organization to be edited}",
        * operationId="UserUpdateOrganization",
        * tags={"P2P Organization Operations"},
        * summary="User P2P organization creation",
        * description="This API allows the user to update the details a P2P organization",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"title", "description","cycle_period","amount_per_member","start_date"},
        *               @OA\Property(property="title", type="string",example="Sample P2P organization"),
        *               @OA\Property(property="description", type="text",example="Sample Description for the P2P organization"),
        *               @OA\Property(property="cycle_period", type="string",example="Weekly/Monthly/Quarterly/Midyear/Yearly"),
        *               @OA\Property(property="amount_per_member", type="string",example="100.00"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Updated new P2P organization  project Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Successfully updated the details of this P2P organization"),
        *              @OA\Property(property="organization",type="string", example="A JSON Object of the details for the organization"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Delete(
        * path="/api/v1/user/project/{unique_id of P2P organization to be deleted}",
        * operationId="RemoveP2POrganization",
        * tags={"P2P Organization Operations"},
        * summary="Remove P2P organization",
        * description="This API allows the logged user to remove P2P organization created by them but has not been initiated or approved. Meaning this organization has to be inactive and must have been created by the logged in user",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully removed P2P organization initiated by the logged in user",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Great, you have successfully removed this organization"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="You are not the team leader of the organization you are trying to remove"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
        *),


        * @OA\Get(
        * path="/api/v1/user/organization/search_users",
        * operationId="SearchRegisteredUsersForP2POrganization",
        * tags={"P2P Organization Operations"},
        * summary="Searching through all registered users to enable the team leader add members to their organizations",
        * description="Searching through all registered users to enable the team leader add members to their organizations",
        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved registered users after the search to select the suitable one for the organization",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="user",type="string", example={"user:[]"}),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),


        * @OA\Post(
        * path="/api/v1/user/organization/member/add/{unique_id_of_p2p_organization}",
        * operationId="AddNewMemberToP2POrganization",
        * tags={"P2P Organization Operations"},
        * summary="Adding new member to the P2P Organization specified by the unique id provided",
        * description="This API allows the team leader of a P2P Organization to add a new member to an organization",
        * @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"member_uuid"},
        *               @OA\Property(property="member_uuid", type="string",example="55970a2d-cc..."),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully added a new member to the P2P organization by the team leader",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="organization",type="string", example="Great, new member has been added to this organization"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),


        * @OA\Get(
        * path="/api/v1/admin/projects/all",
        * operationId="AllProjectsForAdminDashboard",
        * tags={"Admin Operations"},
        * summary="Extracting all the crowdfunding projects",
        * description="Extracting all the crowdfunding projects to be displayed on the dashboard",
        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved all the crowdfunding projects",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="projects",type="string", example={"projects:[]"}),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
        * @OA\Get(
        * path="/api/v1/admin/approve/project/{unique_id of the crowdfunding project}",
        * operationId="ApproveProject",
        * tags={"Admin Operations"},
        * summary="Approving the specified crowdfunding project",
        * description="This enables the admin to approve the crowdfunding project",
        *      @OA\Response(
        *          response=200,
        *          description="Successfully approved a specified crowdfunding project",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You successfully approved this crowdfunding project"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),


        * @OA\Post(
        * path="/api/v1/admin/reject/project",
        * operationId="RejectCrowdfundingProject",
        * tags={"Admin Operations"},
        * summary="Reject a specified Crowdfunding Project",
        * description="This API allows the admin or super admin to reject a specified Crowdfunding Project",
        * @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"unique_id","reason"},
        *               @OA\Property(property="unique_id", type="string",example="55970a2d-cc..."),
        *               @OA\Property(property="reason", type="string",example="Reason for the rejection of the crowdfunding project"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully rejected specified crowdfunding project",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Successfully rejected this crowdfunding project"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),



        * @OA\Get(
        * path="/api/v1/admin/organizations/all",
        * operationId="AllOrganizationsForAdminDashboard",
        * tags={"Admin Operations"},
        * summary="Extracting all the organizations",
        * description="Extracting all the P2P organizations to be displayed on the dashboard",
        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved all the P2P organization",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="organizations",type="string", example={"organizations:[]"}),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
        * @OA\Get(
        * path="/api/v1/admin/approve/organization/{unique_id of the P2P organization}",
        * operationId="ApproveP2POrganization",
        * tags={"Admin Operations"},
        * summary="Approving the specified P2P Organization",
        * description="This enables the admin to approve the P2P Organization",
        *      @OA\Response(
        *          response=200,
        *          description="Successfully approved a specified P2P Organization",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="You successfully approved this P2P organization"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),


        * @OA\Post(
        * path="/api/v1/admin/reject/organization",
        * operationId="RejectP2POrganization",
        * tags={"Admin Operations"},
        * summary="Reject a specified P2P Organization",
        * description="This API allows the admin or super admin to reject a specified P2P Organization",
        * @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"unique_id","reason"},
        *               @OA\Property(property="unique_id", type="string",example="55970a2d-cc..."),
        *               @OA\Property(property="reason", type="string",example="Reason for the rejection of the P2P Organization"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully rejected specified crowdfunding project",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Successfully rejected this P2P Organization"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Error, Bad Request initiated"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

    * @OA\Post(
        * path="/api/v1/admin/category/add",
        * operationId="AdminAddNewCategory",
        * tags={"Admin Settings Operations"},
        * summary="Admin is able to a new category to the project",
        * description="This API allows the admin to add a new category to the project",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"category", "description"},
        *               @OA\Property(property="category", type="string", example="agriculture"),
        *               @OA\Property(property="description", type="text",example="Sample Description"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Added new Category to the Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Your new category has been added"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
        * @OA\Patch(
        * path="/api/v1/admin/category/update/{id of the category to be updated}",
        * operationId="AdminUpdateProjectCategory",
        * tags={"Admin Settings Operations"},
        * summary="Admin updating an existent category in the  project",
        * description="This API allows the admin to update a category",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"category", "description","description"},
        *               @OA\Property(property="category", type="string",example="Environment"),
        *               @OA\Property(property="description", type="text",example="Sample Description"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Added new Crowdfunding project Successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="This category has been updated successfully"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),
                                *),
        * @OA\Get(
        * path="/api/v1/admin/category/show/{id}",
        * operationId="ExtractCategory",
        * tags={"Admin Settings Operations"},
        * summary="Admin extract category",
        * description="This API allows the admin to extract the details of a category project",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved data from the backend about that particular category",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="category:{id:1..., category:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Sorry, Data could not be retrieved"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/admin/category/all",
        * operationId="Extract all the categories by an admin",
        * tags={"Admin Settings Operations"},
        * summary="All Categories in the system",
        * description="This API allows the admin to extract all the categories in the system",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully retrieved all the categories stored in the system",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="projects",type="string", example="categories:{id:...}"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="Bad Request"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Delete(
        * path="/api/v1/admin/category/remove/{id}",
        * operationId="RemoveProjectCategory",
        * tags={"Admin Settings Operations"},
        * summary="Remove category permanently from the system",
        * description="This API allows the logged user to remove categories from the system",

        *      @OA\Response(
        *          response=200,
        *          description="Successfully removed crowdfunding project initiated by the logged in user",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Great, you have successfully removed this category"),
        *           ),
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Error during the processing of the request=>Bad request",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="project",type="string", example="The project you are trying to remove does not belong to you"),
        *           ),
        *       ),
        *       @OA\Response(
        *          response=404,
        *          description="User has not logged into the application yet",
                    @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="failed"),
        *              @OA\Property(property="message",type="string", example="User not found"),
        *           ),
        *       ),
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Get(
        * path="/api/v1/admin/users",
        * operationId="AdminExtractAllDetailsOfAccounts",
        * tags={"Admin Settings Operations"},
        * summary="Admin is able to a extract all the details of user accounts",
        * description="This API allows the admin to extract details of the user accounts in the system",
        *      @OA\Response(
        *          response=201,
        *          description="Extracting details of user accounts Successfully done",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="user_accounts:{}"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Post(
        * path="/api/v1/admin/user/status_change",
        * operationId="AdminChangeStatusOfUserAccount",
        * tags={"Admin Settings Operations"},
        * summary="Admin is able to a change the status of a user account in the system",
        * description="This API allows the admin to change the status of a user account whether to active or inactive",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"user_uuid", "status"},
        *               @OA\Property(property="user_uuid", type="string", example="81a12b6e..."),
        *               @OA\Property(property="status", type="text",example="active/inactive"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="User Account status change successful",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="message",type="string", example="Great, You have successfully changed the status of this user account"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        * @OA\Post(
        * path="/api/v1/user/payment/project/donate",
        * operationId="CrowdfundingDonationPayments",
        * tags={"Crowdfunding Campaign Donation Operations"},
        * summary="A donor can contribute to a crowdfunding project in the system",
        * description="This API allows the a donor to make a contribution to the specified crowdfunding project",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"amount", "project_uuid","email","first_name","last_name"},
        *               @OA\Property(property="amount", type="string", example="100"),
        *               @OA\Property(property="project_uuid", type="string", example="adsfadfasdf..."),
        *               @OA\Property(property="email", type="string", example="example@gmail.com"),
        *               @OA\Property(property="first_name", type="string",example="jane"),
        *               @OA\Property(property="last_name", type="string",example="Doe"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="User payment url generated successfully",
        *          @OA\JsonContent(
        *              @OA\Property(property="status",type="string", example="success"),
        *              @OA\Property(property="paystack response",type="string", example="paystack payment details"),
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
        *      @OA\Response(response=500, description="Internal server error"),
        * ),
                *),

        */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
