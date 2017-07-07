/**
 * Created by Administrator on 2016/1/20 0020.
 */
// ȫѡ start
$('.checkall').click(function(){
    $('.checkall').attr('checked',$(this).attr('checked') == 'checked');
    $('.checkitem').each(function(){
        $(this).attr('checked',$('.checkall').attr('checked') == 'checked');
    });
});
