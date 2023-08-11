{include="header"}

{if="$step == 1"}
    <h1>{LANG="core.welcome"}</h1>
    <h3>{LANG="core.please_fill_to_install"}</h3>
    
    <form action="" method="POST">
        <input type="hidden" name="installaction" value="1">
        <label>{LANG="core.username"}
            <input type="text" name="username">
        </label>
        <label>{LANG="core.email"}
            <input type="text" name="email">
        </label>
        <label>{LANG="core.password"}
            <input type="text" name="password">
        </label>
        <label style="height: auto; margin-bottom: 10px;">{LANG="core.user_folder"}
            <input type="text" name="folder"><br>
            <div>{LANG="core.user_folder_attention"}</div>
        </label>
        <label>{LANG="core.select_theme"}
            <select name="theme">
                {$available_themes}
            </select>
        </label>
        <label>{LANG="core.select_language"}
            <select name="language">
                {$available_languages}
            </select>
        </label>
        <button type="submit">{LANG="core.submit"}</button>
    </form>
{/if}

{if="$step == 2"}
    <h1>{LANG="core.successful_install"}</h1>
    <h3>{if="$errors"}{LANG="core.todo_left_errors"}{else}{LANG="core.todo_left"}{/if}</h3>
    {if="$errors"}
        <ul>
            {loop="errors"}
                <li>{if="$value.perm == 'rename'"}{LANG="core.rename_to"} "{$new_name}": {$value.path}{else}{$value.perm} {LANG="core.for_the_following"} {$value.path}{/if}</li>
            {/loop}
        </ul>
    {/if}
    <button id="go_home">{LANG="core.go_to_homepage"}</button>
    <button id="go_admin">{LANG="core.go_to_admin"}</button>
{/if}
    
{include="footer"}