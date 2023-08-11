{include="header"}
    <body>

        <section id="login" class="form">
            
            <div class="logo"><img src="{$static_link}img/logo_medium.png" alt="UCPanel"></div>
            
            <form class="formee" action="" method="POST">
                
                {if="$error"}
                <div class="formee-msg-error"><h3>{LANG="core.wrong_login"}</h3></div>
                {/if}
                
                <fieldset class="no_margin">
                
                    <input type="hidden" name="action" value="login">
                        
                    <div class="grid-12-12">
                        <label>{LANG="core.username"}</label>
                        <div><input type="text" name="username"></div>
                    </div>
                    <div class="grid-12-12">
                        <label>{LANG="core.password"}</label>
                        <div><input type="password" name="password"></div>
                    </div>
                    <div class="grid-12-12 tc">
                        <button class="button large regular orange" type="submit">{LANG="core.login"}</button>
                    </div>
                    
                </fieldset>
                    
            </form>
            
        </section>

{include="footer"}