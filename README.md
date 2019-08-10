# miaosha
使用php 实现的秒杀系统实例代码 

抢购的处理逻辑

1 校验用户是否登录

2 校验参数是否正确,合法

3.1、校验活动状态信息

3.2 校验问答信息是否正确

4 校验用户是否已经购买

5、校验活活动信息 商品信息是否正常

6、商品信息校验 状态校验

7、商品购买数量限制

8、商品剩余判断

9、减库存 ① 减少redis的缓存收 ②减少数据库库存

10 创建订单信息,保存订单信息

11 秒杀成功 标识一下该用户已经参加过该活动

####  1、开发环境
```
nginx + mysql + redis + php
```

#### 2 数据库文件
```
/data/miaosha.sql
```

#### 3 配置文件
```
/conf/_local.inc.php
```

#### 4 PHP版本
```
7.1
```

#### 5 PHP组件
```
mysql, pdo, redis, mcrypt

brew install php56-redis --build-from-source
brew install php56-mcrypt --build-from-source
brew upgrade php56-igbinary

```

#### 6 站点根目录
```
/web/
```
#### 7 系统管理后台
```
/web/admin/
用户名和密码都是 miaosha  ，代码验证已注释可进行测试
```
