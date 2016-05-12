---
title: 模拟一个比8086还简单的CPU
layout: post
---

### 更精简的指令集

我假设你基本了解8086的指令集。我们将其中必要的一些指令拿出来

    mov
    add
    sub
    not
    and
    or
    jmp
    jcxz
    int
    nop

像 `loop/push/pop/call/ret` 都不是必须的。现在指令只剩12个，所以我们只用4位就可以表示这些指令。

等下，我们的mov指令的种类还是很多的，我们来看一下：

    mov reg,reg               0
    mov [reg],reg  ; save     1
    mov reg,[reg]  ; load     2
    mov reg,idata             3

实际上8086当然具有比这丰富的多的mov指令，但这4种已经足够我们使用。

我们给这四种情况编号。我们将标号 1 和 2 的分别重新映射成 save和load

我们将这几种情况称推广到其他的指令。如add，有两种简单的情形

    add reg,reg       0
    add reg,idata     1

jmp指令也有两种：

    jmp reg
    jmp idata  ; signed int, relative offset

jcxz指令的作用，就是当CX的值为0时，跳转到标号处

    jcxz reg    ; reg's value is the new IP
    jcxz idata  ; signed int, relative offset

寄存器

如8086一样，我们也有14个寄存器：

    AX BX CX DX
    SI DI
    SP BP IP
    CS SS DS ES
    PSW

当然，因为我们抛弃了一些指令，那么一些寄存器就无用了。如抛弃了push，那么SP和SS就无用了。

当然，我们用4位就可以指定一个寄存器。

### 指令结构

接下来我们谈谈我们的指令结构，有两种可能性

    0             4         8                      16
    | instruction |   reg   |         idata         |

    0                       8          12          16
    |      instruction      |   reg 1   |   reg 2   |

第一种用于 mov reg, idata 这种需要立即数的，我们称之为短指令

第二种用于mov reg1,reg2 这种需要两个寄存器的，我们称之为长指令

我们据此来重新理一下指令：

    ---- short instruction ----
    move    0
    add     3
    sub     4
    jmp     8
    jcxz    9
    int     A
    ---- long instruction ----
    mov    F0
    load   F1
    save   F2
    add    F3
    sub    F4
    not    F5
    and    F6
    or     F7
    jmp    F8
    jcxz   F9
    int    FA
    nop    FB

F（全1）开头的指令都是长指令。而所有的同类指令号都相同，使得实现起来方便一些。

### 如何启动CPU

将一个满是数据的可执行文件load进内存之后，CX中保存的是文件的大小。那么我们如何确定CS:IP的起始地址是什么呢？因为我们还没有中断处理，我们就设为内存0处吧。

好了，到现在为止，我们就将所有的要素都讲解完了。接下来讲实现的细节。

我选择用C++做。

首先要解决的是内存的大小。内存估计64M也就够了。但是为了一开始的速度，我们可以用vector，这样可以自适应大小。

而解析指令是这样的：

    void do_ins(unsigned ins)
    {
        unsigned i1    = (ins >> 12) & 0xF;
        unsigned i2    = (ins >>  8) & 0xF;
        unsigned reg1  = (ins >>  4) & 0xF;
        unsigned reg2  = (ins >>  0) & 0xF;
        unsigned idt   = (ins) & 0xFF;
        // printf("idt %X\n", idt);
        int idata = idt & 0x80 ? idt - 0xFF - 1 : idt; // to signed
        // printf("idata %d\n", idata);
        bool is_idata = i1 != 0xF;
        unsigned basic_instr = is_idata ? i1 : i2;
        unsigned reg = i2;
        // printf("instruction: %X %X\n", in, data&0xFF);
        // printf("reg1: %X, reg1 %X\n", reg1, reg2);
        switch (basic_instr) {
        case MOV:

            unsigned pos;
            printf("MOV ");
            if (is_idata)
            {
                // instant data
                regs[reg] = idata;
                printf("%s,%d\n", reg_repr[reg].c_str(), idata);
            } else {
                // reg1 = reg2
                regs[reg1] = regs[reg2];
                printf("%s,%s\n", reg_repr[reg1].c_str(), reg_repr[reg2].c_str());
            }
            regs[IP] += 1;
            break;
        case LOAD:
            // load
            pos = get_pos(DS, reg2);
            regs[reg1] = load(pos);
            printf("LOAD %s,[%s]\n", reg_repr[reg1].c_str(), reg_repr[reg2].c_str());
            regs[IP] += 1;
            break;
        case SAVE:
            // store
            pos = get_pos(DS, reg1);
            store(pos, regs[reg2]);
            printf("SAVE [%s],%s\n", reg_repr[reg1].c_str(), reg_repr[reg2].c_str());
            regs[IP] += 1;
            break;
        case ADD:
            cout<<"ADD"<<endl;
            if (is_idata)
            {
                regs[reg] = regs[reg] + idata;
            } else {
                regs[reg1] = regs[reg1] + regs[reg2];
            }
            regs[IP] += 1;
            break;
        case INC:
            cout<<"INC"<<endl;
            regs[reg1] = regs[reg1] + 1;
            regs[IP] += 1;
            break;
        case SUB:
            cout<<"SUB"<<endl;
            if (is_idata)
            {
                regs[reg] = regs[reg] - idata;
            } else {
                regs[reg1] = regs[reg1] - regs[reg2];
            }
            regs[IP] += 1;
            break;
        case MUL:
            cout<<"MUL"<<endl;
            regs[reg1] = regs[reg1] * regs[reg2];
            regs[IP] += 1;
            break;
        case DIV:
            cout<<"DIV"<<endl;
            regs[AX] = regs[reg1] / regs[reg2];
            regs[DX] = regs[reg1] % regs[reg2];
            regs[IP] += 1;
            break;
        case AND:
            cout<<"AND"<<endl;
            regs[reg1] = regs[reg1] & regs[reg2];
            regs[IP] += 1;
            break;
        case OR:
            cout<<"OR"<<endl;
            regs[reg1] = regs[reg1] | regs[reg2];
            regs[IP] += 1;
            break;
        case JCXZ:
            printf("JCXZ :%X?\n", regs[CX]);
            if (regs[CX] == 0) {
                regs[IP] += 1;
                break;
            }
        case JMP:
            printf("JMP ");
            if (is_idata)
            {
                regs[IP] += idata;
                printf("%d\n", idata);
            } else {
                regs[IP] = regs[reg1];
                printf("[%s]\n", reg_repr[reg1].c_str());
            }
            // char ccc ; cin>>ccc;
            break;
        case INT:
            switch (regs[idata]) {
                case 0:
                    runing = false;
                    break;
            }
        case NOP:
            regs[IP] += 1;
            break;
        }
    }

其他并没有什么困难之处。
我做了一个简单的demo，放到了github上：

[GitHub - picasso250/8086simu: 一个比8086CPU还要简单的CPU模拟器](https://github.com/picasso250/8086simu)

test.asm文件是一个简单的测试（一个病毒），而compile文件可以将asm文件编译成二进制文件。8080simu可以执行这个二进制文件。

更详细的指令集可以参看 README

PS：为什么我要无聊到编写CPU模拟器的这种地步呢？我的最终目的是做一个电子演化的实验。致敬《失控》。
