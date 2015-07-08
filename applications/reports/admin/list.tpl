<section class="titre-profil admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$lang.reports}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.reports}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label class="inputs-label">{$lang.from_date}</label>
                <div class="inputs">
                    <input type="text" data-date-format="mm/dd/yy" class="" value="{$smarty.post.from_date}" name="from_date" />
                </div>
            </div>

            <div class="input-group">
                <label class="inputs-label">{$lang.to_date}</label>
                <div class="inputs">
                    <input type="text" class="" value="{$smarty.post.to_date}" name="to_date" />
                </div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" name="report" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.generate_report}</button>
            </div>
        </form>

        {if isset($reportData)}
            {if $reportData}
                <div class="row-input">
                    <label for="datepick">{$lang.results}</label>
                    <div class="inputs">
                        <div id="placeholder" style="width:600px;height:300px;float:left;"></div>
                        <p id="choices" style="float: left;">{$lang.graph}:</p>
                    </div>
                </div>

                {literal}
                    <script id="source">
                        $(function () {
                            var datasets = {
                                "Total": {
                                    label: "{/literal}{$lang.grid_total}{literal}",
                                    data: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, {$r.total}]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}]
                                },

                                "Receive": {
                                    label: "{/literal}{$lang.grid_receive}{literal}",
                                    data: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, {$r.receive}]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}]
                                },

                                "Referal": {
                                    label: "{/literal}{$lang.grid_referal}{literal}",
                                    data: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, {$r.referal}]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}]
                                },

                                "Win": {
                                    label: "{/literal}{$lang.grid_win}{literal}",
                                    data: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, {$r.win}]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}]
                                },

                                "Deposit": {
                                    label: "{/literal}{$lang.grid_deposit}{literal}",
                                    data: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, {$r.deposit}]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}]
                                },

                                "Withdraw": {
                                    label: "{/literal}{$lang.grid_withdraw}{literal}",
                                    data: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, {$r.withdraw}]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}]
                                }
                            };

                            var options = {
                                xaxis: { mode: "time", ticks: [{/literal}{foreach from=$reportData item=r key=k name=foo}[{$smarty.foreach.foo.index}, "{$k}"]{if !$smarty.foreach.foo.last},{/if}{/foreach}{literal}] },
                                selection: { mode: "xy" },
                                grid: { hoverable: true, clickable: true },
                                series: { lines: { show: true }, points: { show: true } }
                            };

                            var plot = $.plot($("#placeholder"), datasets, options);

                            function showTooltip(x, y, contents) {
                                $('<div id="tooltip">' + contents + '</div>').css( {
                                    position: 'absolute',
                                    display: 'none',
                                    top: y + 5,
                                    left: x + 5,
                                    border: '1px solid #fdd',
                                    padding: '2px',
                                    'background-color': '#fee',
                                    opacity: 0.80
                                }).appendTo("body").fadeIn(200);
                            }

                            var previousPoint = null;

                            $("#placeholder").bind("plothover", function (event, pos, item) {
                                $("#x").text(pos.x.toFixed(2));
                                $("#y").text(pos.y.toFixed(2));

                                if(item) {
                                    if(previousPoint != item.datapoint) {
                                        previousPoint = item.datapoint;

                                        $("#tooltip").remove();
                                        var x = item.datapoint[0].toFixed(2),
                                        y = item.datapoint[1].toFixed(2);

                                        showTooltip(item.pageX, item.pageY, item.series.label + " ({/literal}{$currency.symbol}{literal} " + y + ")");
                                    }
                                }

                                else {
                                    $("#tooltip").remove();
                                    previousPoint = null;
                                }
                            });

                            var i = 0;
                                $.each(datasets, function(key, val) {
                                val.color = i;
                                ++i;
                            });

                            var choiceContainer = $("#choices");

                            $.each(datasets, function(key, val) {
                                choiceContainer.append('<br/><input type="checkbox" name="' + key +
                                '" checked="checked" id="id' + key + '">' +
                                '<label for="id' + key + '">'
                                + val.label + '</label>');
                            });

                            choiceContainer.find("input").click(plotAccordingToChoices);

                            function plotAccordingToChoices() {
                                var data = [];

                                choiceContainer.find("input:checked").each(function () {
                                    var key = $(this).attr("name");
                                    if (key && datasets[key])
                                    data.push(datasets[key]);
                                });

                                if (data.length > 0)
                                $.plot($("#placeholder"), data, options);
                            }

                            plotAccordingToChoices();
                        });
                    </script>
                {/literal}
            {/if}
        {/if}
    </div>
</section>