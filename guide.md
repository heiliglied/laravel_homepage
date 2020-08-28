## 기본 소스 작성 가이드.
-----------------
1. routing
 - 기본 라우팅 구성은 routes/web.php에서 작성.
 - 같은 prefix를 지닌 router가 많을 경우 반드시 group으로 묶어 분류 한다.
 - router 별칭은, 비동기를 사용하지 않을 경우에만 사용한다. javascript파일을 별도 분리할 경우 mustache 템플릿 이상동작이 우려됨.
 
2. static 파일.
 - 기본은 laravel-mix를 이용하여 webpack.mix.js 파일에 구성한다.
 - 반드시 사전에 읽어와야 할 라이브러리일 경우 vendor에 기본으로 읽어오도록 추가한다.
 - es6를 이용할 경우 반드시 mix.babel을 이용하여 웹 호환성을 유지해 주어야 한다.
 - 공통 라이브러리와, 페이지별 전용 스크립트는 반드시 분류해 주어야 한다.
 
3. view 파일.
 - 각 용도에 맞도록 별도의 폴더로 반드시 구분한다.
 - 별도의 기준이 없을 시, 신규로 폴더를 생성하여 사용한다.
 - errors 폴더는 라라벨이 기본으로 제공하는 에러페이지 이므로, 별도로 필요할 시 http 에러코드명으로 생성한다(ex:419.blade.php)

4. controller.
 - 기본 Controller 이외에는 전부 각 분류별로 폴더를 생성한다.
 - 사용하지 않더라도 __construct() 매직 메소드는 생성해 둔다.
 - 되도록 미들웨어 필터링은 router에서 사용하도록 한다.
 
5. model.
 - 인증에 필요한 인증용 model을 제외하고는 전부 Models 폴더에 생성한다.
 
6. interface, traits.
 - 각 인터페이스 및 trait 파일을 모아둔다.
 
7. Services.
 - ORM등 쿼리 작업 파일들을 이쪽에서 모아 처리한다.
 
8. table schema.
 - database/migration 폴더에 table 스키마 소스 작성해서 artisan 명령어로 관리한다. 
 
9. 다국어 지원.
 - resources/lang 폴더에 ko폴더를 만든 후, 기본값인 en폴더의 파일을 복사해서, 필요한 값을 한글로 치환한 후 사용.
 
## 관리자 페이지 작성 가이드.
--------------------
1. 인증.
 - config/auth.php 에 admin guard, provider 추가 후, 별도의 admin 인증 추가함.
 - auth 미들웨어에 별도로 auth:admin 으로 관리자 인증 분리함.
 
2. 컨트롤러.
 - view 페이지 작성시 interfaces의 AsideMenuInterface를 상속하여, activeMenuList를 구현해 줌.
 - active 상태의 메뉴값을 전달하셔 사용함.
 - activeMenuList로 구현해서 반환받은 메뉴값을 변수로 view에 전달해야 함.
 
