            <tr>
                <th class="thHead" colspan="2">{L_ADD_A_POLL}</th>
            </tr>
            <tr>
                <td class="row1" colspan="2"><span class="gensmall">{L_ADD_POLL_EXPLAIN}</span></td>
            </tr>
            <tr>
                <td class="row1"><span class="gen"><strong>{L_POLL_QUESTION}</strong></span></td>
                <td class="row2"><span class="genmed"><input type="text" name="poll_title" size="50" maxlength="255" class="post" value="{POLL_TITLE}" /></span></td>
            </tr>
            <!-- BEGIN poll_option_rows -->
            <tr>
                <td class="row1"><span class="gen"><strong>{L_POLL_OPTION}</strong></span></td>
                <td class="row2"><span class="genmed"><input type="text" name="poll_option_text[{poll_option_rows.S_POLL_OPTION_NUM}]" size="50" class="post" maxlength="255" value="{poll_option_rows.POLL_OPTION}" /></span> &nbsp;<input type="submit" name="edit_poll_option" value="{L_UPDATE_OPTION}" class="titaniumbutton" /> <input type="submit" name="del_poll_option[{poll_option_rows.S_POLL_OPTION_NUM}]" value="{L_DELETE_OPTION}" class="titaniumbutton" /></td>
            </tr>
            <!-- END poll_option_rows -->
            <tr>
                <td class="row1"><span class="gen"><strong>{L_POLL_OPTION}</strong></span></td>
                <td class="row2"><span class="genmed"><input type="text" name="add_poll_option_text" size="50" maxlength="255" class="post" value="{ADD_POLL_OPTION}" /></span> &nbsp;<input type="submit" name="add_poll_option" value="{L_ADD_OPTION}" class="titaniumbutton" /></td>
            </tr>
            <tr>
                <td class="row1"><span class="gen"><strong>{L_POLL_LENGTH}</strong></span></td>
                <td class="row2"><span class="genmed"><input type="text" name="poll_length" size="3" maxlength="3" class="post" value="{POLL_LENGTH}" /></span>&nbsp;<span class="gen"><strong>{L_DAYS}</strong></span> &nbsp; <span class="gensmall">{L_POLL_LENGTH_EXPLAIN}</span></td>
            </tr>
            <tr>
        <td class="row1"><span class="gen"><strong>{L_POLL_TOGGLE}</strong></span></td>
        <td class="row2"><span class="genmed"><input type="checkbox" name="poll_view_toggle" value="1" {POLL_TOGGLE_CHECKED} /></span><span class="gen"></span> &nbsp; <span class="gensmall">{L_POLL_TOGGLE_EXPLAIN}</span></td>
    </tr>
            <!-- BEGIN switch_poll_delete_toggle -->
            <tr>
                <td class="row1"><span class="gen"><strong>{L_POLL_DELETE}</strong></span></td>
                <td class="row2"><input type="checkbox" name="poll_delete" /></td>
            </tr>
            <!-- END switch_poll_delete_toggle -->