<div class="copyright-box">
    <div class="links">
        @foreach(getPositionNav('bottom') as $item)
            <a href="{{$item['url']}}" rel="nofollow" @if($item['opennew'])target="_blank" @else target="_self"@endif>{{ getLangData($item,'name')}}</a>@if (!$loop->last)|@endif
        @endforeach
    </div>
    <div class="copyright"> @if(!empty(getConfig('copyright'))){{getConfig('copyright')}}@endif @if(!empty(getConfig('icp_number')))&nbsp;ICP备案证书号:{{getConfig('icp_number')}}@endif @if(!empty(getConfig('powered_by')))&nbsp;POWERED by:{{getConfig('powered_by')}} @endif</div>
</div>

<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?290604a51a216541a028e71ad5663423";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();

    (function(){
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https'){
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        }
        else{
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
</script>
<script>

</script>
