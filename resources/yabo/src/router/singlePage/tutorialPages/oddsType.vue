<template>
  <div class="body">
    <div class="header">体育博彩规定与规则</div>

    <div class="content">
      <span class="subTitle">赔率类型</span>
      <div class="details">
        <span class="subTitle">欧洲赔率</span>
        <ul>
          <li>欧洲赔率是一个大于 1 的正数。它代表赢得的回报率已包括投注本金在内。</li>
          <li>范例：欧洲赔率 1.96 表示如果下注 $100, 可获得盈利为 $96而可损失资金为 $100。</li>
        </ul>
        <span class="subTitle">香港赔率</span>
        <ul>
          <li>香港赔率是一个大于 0 的正数。它代表赢得的回报率不包括投注本金在内。</li>
          <li>范例：香港赔率 0.96 表示如果下注 $100, 可获得盈利为 $96而可损失资金为 $100。</li>
        </ul>
        <span class="subTitle">马来赔率</span>
        <ul>
          <li>马来赔率为正值时和香港赔率算法一样, 为负值时它代表要赢回投注本金全额的付出率。</li>
          <li>范例：马来赔率 0.96 表示如果下注 $100, 可获得盈利为 $96而可损失资金为 $100。马来赔率 -0.96表示如果下注 $100, 可获得盈利为 $100而可损失资金为 $96。</li>
        </ul>
        <span class="subTitle">印尼赔率</span>
        <ul>
          <li>印尼赔率是一个绝对值大于 1 的数字。印尼赔率为正值时和香港赔率算法一样, 为负值时它代表要赢回投注本金全额的付出率。</li>
          <li>范例：印尼赔率 1.96 表示如果下注 $100, 可获得盈利为 $196而可损失资金为 $100。印尼赔率 -1.96表示如果下注 $100, 可获得盈利为 $100而可损失资金为 $196。</li>
        </ul>
        <span>请参考以下赔率类型的计算方式</span><br>
        <img src="@/assets/tutorials/oddsType1.webp" /><br>
        <span class="subTitle">赔率换算表</span><br>
        <img src="@/assets/tutorials/oddsType2.webp" />
      </div>
    </div>
  </div>
</template>